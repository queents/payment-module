<?php


namespace Modules\Payment\Http\Services;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Payment\Entities\Payment;
use Modules\Payment\Enums\PaytabEnum;
use Modules\Payment\Events\CallBackEvent;
use Modules\Payment\Events\PaytabsCallBackEvent;
use Modules\Payment\Events\PaytabsRequestCreatedEvent;
use Modules\Payment\Http\Helpers\ApiResponse;
use Modules\Payment\Http\Helpers\HttpHelper;
use Modules\Payment\Http\Helpers\PaymentSaveToLogs;
use Modules\Payment\Http\Interfaces\IPaymentInterface;

/**
 *
 */
class PayTabsPaymentService implements IPaymentInterface
{
    use HttpHelper,PaymentSaveToLogs;

    /**
     * @var mixed
     */
    private mixed $merchantRefNum;

    /**
     * @var array
     */
    private array $integrations;

    /**
     * @var array
     */
    private Collection $data ;

    /**
     * @param $integrations
     */
    public function __construct($integrations)
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
    public function validate(array $attributes):self {

        $validation =Validator::make($attributes, PaytabEnum::VALIDATION);

        if ($validation->fails()) {
            throw ValidationException::withMessages($validation->errors()->messages());
        }

        $this->data = collect($validation->validated());
        return $this;
    }

    /**
     * @return $this
     */
    public function init():self
    {
        // in http helper
        $this->header["authorization"] = $this->integrations['secret_key'];

        $this->data = [

            'profile_id' => $this->integrations['profile_id'],
            "cart_id" =>(string) $this->merchantRefNum,
            "tran_type"=>          PaytabEnum::PAYTABS_TYPE,
            "tran_class"=>         PaytabEnum::PAYTABS_CLASS,
            "cart_currency"=>      "EGP",
            "cart_amount"=> $this->data["amount"],
            "cart_description"=>   $this->data["notes"],
            "callback"=>           PaytabEnum::PAYTABS_CLLBACK,
            "return"=>             $this->data["returnUrl"]
        ];

        event(new PaytabsRequestCreatedEvent($this->data));

        return $this;
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pay():array
    {
        $response=$this->post(PaytabEnum::PAYTABS_DOMAIN,$this->data);
        $response['data']=json_decode($response['data']);
        return $response;
    }

    /**
     * @param $request
     * @return void
     */
    public function callBack($request): void
    {
        event(new PaytabsCallBackEvent($request));
    }

    /**
     * @return $this
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function saveToPayment():self
    {
        $user=auth()->user();
        $record= Payment::create(
            [
                "model_id"=>$user->id,
                "model_table" => $user->getTable(),
                "order_id" =>request()->get("order_id"),
                "order_table" =>request()->get('order_table'),
                "payment_method_id" => request()->get("paymentMethodId"),
                "payment_status_id" => 3,
                "amount" =>request()->get("amount"),
                "notes" =>request()->get('notes')

            ]
        );
        if ($record) {
            $this->merchantRefNum = $record->id;
        }

        return $this;
    }
}
