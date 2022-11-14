<?php

namespace Modules\Payment\Http\Services;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

use Modules\Payment\Entities\Payment;
use Modules\Payment\Entities\PaymentStatus;
use Modules\Payment\Enums\FawryEnum;
use Modules\Payment\Events\FawryCallBackEvent;
use Modules\Payment\Events\FawryRequestCreatedEvent;
use Modules\Payment\Http\Helpers\HttpHelper;
use Modules\Payment\Http\Helpers\PaymentSaveToLogs;
use Modules\Payment\Http\Interfaces\IFawryInterface;
use Modules\Payment\Http\Interfaces\IPaymentInterface;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;

/**
 *
 */
class FawryPlusPaymentService implements IPaymentInterface, IFawryInterface
{

    use HttpHelper,PaymentSaveToLogs;

    /**
     * @var array
     */
    private array $integrations;

    /**
     * @var Collection
     */
    private Collection $data ;

    /**
     * @var int
     */
    private int $merchantRefNum ;

    /**
     * constructor get the data from factory and adapt it as array
     * @param Collection $integrations
     */
    public function __construct(Collection $integrations)
    {
        foreach ($integrations as $integration) {
            $this->integrations[$integration->key] = $integration->value;
        }
    }


    /**
     * @param array $attributes
     * @return $this
     * @throws ValidationException
     *
     */
    public function validate(array $attributes): self
    {
        $validation =Validator::make($attributes, FawryEnum::VALIDATION);

        if ($validation->fails()) {
            throw ValidationException::withMessages($validation->errors()->messages());

        }

        $this->data = collect($validation->validated());
        return $this;
    }


    /**
     *
     * initial the request and return self object
     * @return $this
     */
    public function init():self
    {
        $this->data = collect($this->data->only(['returnUrl', 'chargeItems','paymentMethodId']))->merge([
            'merchantCode' => $this->integrations['merchant_code'],
            "merchantRefNum" => $this->merchantRefNum,
            "authCaptureModePayment"=>false,
            'customerMobile' => auth()->user()->phone??"",
            'customerEmail' => auth()->user()->email??"",
        ]);
        $this->data["paymentMethod"]=FawryEnum::PAYMENT_METHODS[$this->data["paymentMethodId"]];
        $this->data['signature'] = $this->generateSignature();

        event(new FawryRequestCreatedEvent($this->data));

        return $this;
    }


    /**
     *
     * call the http post method and return status ,data and message
     * check if the app is local to add integration url
     */

    public function pay():array
    {
        return $this->post(
            App::environment(['local', 'staging'])
            ?$this->integrations['testing_uri']
            :$this->integrations['FAWRY_PR_URI'], $this->data);
    }


    /**
     *
     * depending on what the callback will do
     *
     * event and the user deal with the response if PAID,EXPIRED etc
     *
     *
     */
    public function callBack($request): void
    {
        try {
            $payment= Payment::with(['paymentStatus'])->find($request['merchantRefNumber']);
            $status=PaymentStatus::select('id')->whereJsonContains('name', ["en"=>$request['orderStatus']])->first()->id;
            $payment->payment_status_id = $status;
            $payment->transaction_code = $request['fawryRefNumber'];
            $payment->save();
        }catch (\Exception $e) {
            $this->saveToLogs($request->all(), $e->getMessage());
        }

        event(new FawryCallBackEvent($request));
    }


    /**
     * @return $this
     */
    public function saveToPayment():self
    {
        $user=auth()->user();
        $record=Payment::create(
            [
                "model_id"=>$user->id,
                "model_table" => $user->getTable(),
                "order_id" =>$this->data['order_id'],
                "order_table" =>$this->data['order_table'],
                "payment_method_id" => $this->data['paymentMethodId'],
                "payment_status_id" => 3,
                "amount" =>$this->data['amount'],
                "notes" =>$this->data['notes']

            ]
        );

        if ($record)
            $this->merchantRefNum=$record->id;

        return $this;
    }

    /**
     *
     * generating signature that fawry want for auth
     * @return string
     *  */

    public function generateSignature():string
    {
        $data = collect($this->data['chargeItems']);

        $items = $data->map(function ($item) {
            return $item['itemId'] . $item['quantity'] . number_format((float)$item['price'],2,'.','');
        })->join('');

        return hash(
            'sha256',
            $this->data['merchantCode'] .
                $this->data['merchantRefNum'] .
                $this->data['returnUrl'] .
                $items .
                $this->integrations['security_key']
        );
    }
}
