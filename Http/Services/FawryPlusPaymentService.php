<?php

namespace Modules\Payment\Http\Services;

use Modules\Payment\Entities\PaymentStatus;
use Modules\Payment\Events\CallBackEvent;
use Modules\Payment\Http\Helpers\HttpHelper;
use Modules\Payment\Http\Helpers\PaymentSaveToLogs;
use Modules\Payment\Http\Interfaces\IFawryInterface;
use Modules\Payment\Http\Interfaces\IPaymentInterface;
use Modules\Payments\Entities\Payment;

class FawryPlusPaymentService implements IPaymentInterface, IFawryInterface
{

    use HttpHelper,PaymentSaveToLogs;


    private $uri;
    private $secretKey;
    private $merchantCode;
    private $integrations;
    private $data = [];
    private $merchantRefNum ;
    private $paymentMethods=[
        "2" => "PayAtFawry",
        "1" => "CARD"
    ];
    private const VALIDATION=["order_id",
        "order_table",
        "paymentMethodId","amount","notes","returnUrl","chargeItems"];

    public function validate() {

        if(request()->has(self::VALIDATION)){
            return $this;
        }

        throw new \Exception("You Have Messing Parameters");

    }
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

        $this->data = collect($attributes->only(['returnUrl', 'chargeItems']))->merge([
            'merchantCode' => $this->integrations['merchant_code'],
            "merchantRefNum" => $this->merchantRefNum
        ]);
        $this->data["paymentMethod"]=$this->paymentMethods[$attributes->get("paymentMethodId")];
        $this->data['signature'] = $this->generateSignature();

        return $this;
    }


    /**
     *
     * call the http post method and return status ,data and message
     *
     */

    public function pay()
    {
        return $this->post($this->integrations['testing_uri'], $this->data);
    }


    /**
     *
     * depending of what the callback will do
     *
     * event and the user deal with the response if PAID,EXPIRED etc
     *
     *
     */


    public function callBack($request)
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


        event(new CallBackEvent($request));
    }


    public function saveToPayment()
    {
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

    /**
     *
     * generating signature that fawry want for auth
     *
     *  */

    public function generateSignature()
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
