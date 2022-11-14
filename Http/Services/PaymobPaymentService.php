<?php

namespace Modules\Payment\Http\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Payment\Entities\Payment;
use Modules\Payment\Enums\PaymobEnum;
use Modules\Payment\Events\CallBackEvent;
use Modules\Payment\Events\fawryRequestCreatedEvent;
use Modules\Payment\Events\PaymobCallBackEvent;
use Modules\Payment\Events\PaymobRequestCreatedEvent;
use Modules\Payment\Http\Helpers\HttpHelper;
use Modules\Payment\Http\Helpers\PaymentSaveToLogs;
use Modules\Payment\Http\Interfaces\IPaymentInterface;
use Modules\Payment\Http\Interfaces\IPaymobInterface;

class PaymobPaymentService implements IPaymentInterface,IPaymobInterface
{
    use HttpHelper,PaymentSaveToLogs;

    private array $integrations;
    private Collection $attributes;
    private mixed $paymentToken;
    private int $merchantRefNum;
    private Collection $data;

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
    public function validate(array $attributes):self
    {

        $validation =Validator::make($attributes, PaymobEnum::VALIDATION);
        if ($validation->fails()) {
            throw ValidationException::withMessages($validation->errors()->messages());
        }
        $this->data = collect($validation->validated());
        return $this;
    }

    /**
     *
     * initiate the request and return self object
     * PayMob consist of 3 steps
     * @throws \Exception
     */

    public function init():self
    {
        try {
            $authToken=$this->authenticationRequest();
            $order=$this->orderRegistrationAPI($authToken->token);
            $this->paymentToken=$this->PaymentKeyRequest($order,$authToken->token);
        }catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }

        return $this;
    }


    /**
     *
     * call the http post method and return status ,data and message
     *
     */
    public function pay():array
    {
        $res = [
            "status" => true,
            'data' => PaymobEnum::PAYMOB_DOMAIN.PaymobEnum::IFRAME_REQUEST
                . $this->integrations['PAYMOB_IFRAME_ID']
                . "?payment_token="
                . $this->paymentToken->token];

        return $res;
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

        $paymentId=explode("Paymob",$request["merchant_order_id"]);

        try {
            if ($request["success"] == 'true' && $request["data_message"] == 'Approved') {
                $payment= Payment::where("id",$paymentId[0])->where('amount',$request["amount"])->first();
                $payment->payment_status_id = "PAID";
                $payment->save();

            }
            else {
                $this->saveToLogs($request, $request["data_message"] );
            }
        }catch (\Exception $e) {
            $this->saveToLogs($request, $e->getMessage());
        }

        event(new PaymobCallBackEvent($request));
    }


    /**
     * save payment to table
     * and generate reference code
     * @return $this
     */
    public function saveToPayment():self{

        $user=auth()->user();

        $record= Payment::create(
            [
                "model_id"=>$user->id,
                "model_table" => $user->getTable(),
                "order_id" =>$this->data["order_id"],
                "order_table" =>$this->data['order_table'],
                "payment_method_id" => $this->data["paymentMethodId"],
                "payment_status_id" => 3,
                "amount" =>$this->data["amount"],
                "notes" =>$this->data['notes']
            ]
        );

        if ($record) {
            $this->merchantRefNum = $record->id;
        }

        return $this;
    }


    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function authenticationRequest(): mixed
    {
       $result= $this->post(PaymobEnum::PAYMOB_DOMAIN.PaymobEnum::TOKENS_REQUEST, ["api_key" => $this->integrations['PAYMOB_API_KEY']]);

       if ($result['status']) {
           return json_decode($result['data']);
       }

        throw new \RuntimeException($result['message']);

    }


    public function orderRegistrationAPI(string $token): mixed
    {
        $result =
            $this->post(PaymobEnum::PAYMOB_DOMAIN.PaymobEnum::ORDER_REQUEST,
                ["auth_token" => $token,
                    "delivery_needed" => "false",
                    "amount_cents" => $this->data['amount'],
                    "currency"=> "EGP",
                    "merchant_order_id"=> $this->merchantRefNum."Paymob".rand(10,9999),
                    "items" => []
                ]);

        if ($result['status']) {
            return json_decode($result['data']);
        }

        throw new \RuntimeException($result['message']);

    }

    /**
     * @param mixed $order
     * @param string $token
     * @return mixed
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     *
     * create request and fir event
     * the event push data by reference
     */
    public function PaymentKeyRequest(mixed $order, string $token): mixed
    {
        $request=["auth_token" => $token,
            "expiration" => 36000,
            "amount_cents" => $order->amount_cents,
            "order_id" => $order->id,
            "billing_data" =>
                ["apartment" => "NA",
                    "email" => 'NA',
                    "floor" => "NA",
                    "first_name" => $this->data['first_name'],
                    "street" => "NA",
                    "building" => "NA",
                    "phone_number" => $this->data['phone'],
                    "shipping_method" => "NA",
                    "postal_code" => "NA",
                    "city" => "NA",
                    "country" => "NA",
                    "last_name" => $this->data['last_name'],
                    "state" => "NA"],
            "currency" => "EGP",
            "integration_id" => env('PAYMOB_MODE') == "live" ? $this->integrations['PAYMOB_LIVE_INTEGRATION_ID'] : $this->integrations['PAYMOB_SANDBOX_INTEGRATION_ID'] ];

        event(new PaymobRequestCreatedEvent($request));

        $result = $this->post(PaymobEnum::PAYMOB_DOMAIN.PaymobEnum::PAYMENT_TOKEN_REQUEST,$request);

        if ($result['status']) {
            return json_decode($result['data']);
        }

        throw new \RuntimeException($result['message']);

    }


}
