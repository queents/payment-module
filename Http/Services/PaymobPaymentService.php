<?php

namespace Modules\Payment\Http\Services;

use Modules\Payment\Enums\PaymobEnum;
use Modules\Payment\Events\CallBackEvent;
use Modules\Payment\Http\Helpers\HttpHelper;
use Modules\Payment\Http\Helpers\PaymentSaveToLogs;
use Modules\Payment\Http\Interfaces\IPaymentInterface;
use Modules\Payment\Http\Interfaces\IPaymobInterface;
use Modules\Payment\Http\Requests\PaymobRequest;
use Modules\Payments\Entities\Payment;

class PaymobPaymentService implements IPaymentInterface,IPaymobInterface
{
    use HttpHelper,PaymentSaveToLogs;


    private $integrations;
    private $attributes;
    private $paymentToken;
    private $merchantRefNum;
    private $data = [];
    private const VALIDATION=["order_id",
                                "order_table",
                                "paymentMethodId","amount","notes","first_name","last_name","phone"];

    /*
        constractor get the data from config file that provided from service provider

     */

    public function __construct($integrations)
    {
        foreach ($integrations as $integration)
            $this->integrations[$integration->key] = $integration->value;
    }


    /**
     *
     * initiat the request and return self object
     */

    public function init($attributes)
    {
        $this->attributes = $attributes;

        try {
            $authToken=$this->authenticationRequest();
            $order=$this->orderRegistrationAPI($authToken->token);
            $this->paymentToken=$this->PaymentKeyRequest($order,$authToken->token);

        }catch (\Exception $e) {

            throw new \Exception($e->getMessage());

        }

        return $this;
    }


    /**
     *
     * call the http post method and return status ,data and message
     *
     */

    public function pay()
    {

        $res = ["status" => true,
            'data' => PaymobEnum::PAYMOB_DOMAIN.PaymobEnum::IFRAME_REQUEST
                . $this->integrations['PAYMOB_IFRAME_ID']
                . "?payment_token=" . $this->paymentToken->token];

        return $res;
    }


    /**
     *
     * depending of what the callback will do
     *
     * event and the user deal with the response if PAID,EXPIRED etc
     *
     *https://apimeetmot.emalleg.net/api/callbackPayMob
     */
    public function callBack($request)
    {

        $paymentId=explode("Paymob",$request["merchant_order_id"]);

        try {
            if ($request["success"] == 'true' && $request["data_message"] == 'Approved') {
                $payment= Payment::where("id",$paymentId[0])->where('amount',$request["amount"])->first();
                $payment->payment_status_id = "PAID";
                $payment->save();

            }else
                $this->saveToLogs($request, $request["data_message"] );

        }catch (\Exception $e) {

            $this->saveToLogs($request, $e->getMessage());
        }

        event(new CallBackEvent($request));
    }
    public function saveToPayment(){

        $user=auth()->user();

        $record=Payment::create(
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

        if ($record)
            $this->merchantRefNum=$record->id;

        return $this;
    }


    public function authenticationRequest(): mixed
    {
       $result= $this->post(PaymobEnum::PAYMOB_DOMAIN.PaymobEnum::TOKENS_REQUEST, ["api_key" => $this->integrations['PAYMOB_API_KEY']]);

       if ($result['status'])
            return json_decode($result['data']);

        throw new \Exception($result['message']);

    }


    public function orderRegistrationAPI(string $token): mixed
    {
        $result =
            $this->post(PaymobEnum::PAYMOB_DOMAIN.PaymobEnum::ORDER_REQUEST,
                ["auth_token" => $token,
                    "delivery_needed" => "false",
                    "amount_cents" => $this->attributes->amount,
                    "currency"=> "EGP",
                    "merchant_order_id"=> $this->merchantRefNum."Paymob".rand(10,9999),
                    "items" => []
                ]);

        if ($result['status'])
            return json_decode($result['data']);

        throw new \Exception($result['message']);

    }

    public function PaymentKeyRequest(mixed $order,string $token): mixed
    {
        $result =
            $this->post(PaymobEnum::PAYMOB_DOMAIN.PaymobEnum::PAYMENT_TOKEN_REQUEST,
                ["auth_token" => $token,
                    "expiration" => 36000,
                    "amount_cents" => $order->amount_cents,
                    "order_id" => $order->id,
                    "billing_data" =>
                        ["apartment" => "NA",
                            "email" => 'NA',
                            "floor" => "NA",
                            "first_name" => $this->attributes->first_name,
                            "street" => "NA",
                            "building" => "NA",
                            "phone_number" => $this->attributes->phone,
                            "shipping_method" => "NA",
                            "postal_code" => "NA",
                            "city" => "NA",
                            "country" => "NA",
                            "last_name" => $this->attributes->last_name,
                            "state" => "NA"],
                    "currency" => "EGP",
                    "integration_id" => env('PAYMOB_MODE') == "live" ? $this->integrations['PAYMOB_LIVE_INTEGRATION_ID'] : $this->integrations['PAYMOB_SANDBOX_INTEGRATION_ID'] ]);

        if ($result['status'])
            return json_decode($result['data']);

        throw new \Exception($result['message']);

    }

    public function validate() {

        if(request()->has(self::VALIDATION)){
            return $this;
        }

        throw new \Exception("You Have Messing Parameters");

    }
}
