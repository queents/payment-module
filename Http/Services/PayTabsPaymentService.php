<?php


namespace Modules\Payment\Http\Services;


use Illuminate\Support\Facades\Http;
use Modules\Payment\Enums\PaytabEnum;
use Modules\Payment\Events\CallBackEvent;
use Modules\Payment\Http\Helpers\ApiResponse;
use Modules\Payment\Http\Helpers\HttpHelper;
use Modules\Payment\Http\Helpers\PaymentSaveToLogs;
use Modules\Payment\Http\Interfaces\IPaymentInterface;
use Modules\Payments\Entities\Payment;

class PayTabsPaymentService implements IPaymentInterface
{
    use HttpHelper,PaymentSaveToLogs;


    private  $merchantRefNum;
    private $integrations;
    private $data = [];
    private const VALIDATION=["order_id",
        "order_table",
        "paymentMethodId","amount","notes","returnUrl","notes","cart_currency"];


    public function __construct($integrations)
    {
        foreach ($integrations as $integration)
            $this->integrations[$integration->key] = $integration->value;
    }


    public function init($attributes)
    {
        $this->header["authorization"] = $this->integrations['secret_key'];


        $this->data = [

            'profile_id' => $this->integrations['profile_id'],
            "cart_id" =>(string) $this->merchantRefNum,
            "tran_type"=>          PaytabEnum::PAYTABS_TYPE,
            "tran_class"=>         PaytabEnum::PAYTABS_CLASS,
            "cart_currency"=>      $attributes['cart_currency'],
            "cart_amount"=> $attributes["amount"],
            "cart_description"=>   $attributes["notes"],
            "callback"=>           PaytabEnum::PAYTABS_CLLBACK,
            "return"=>             $attributes["returnUrl"]
        ];

        return $this;
    }

    public function pay()
    {
        $response=$this->post(PaytabEnum::PAYTABS_DOMAIN,$this->data);
        $response['data']=json_decode($response['data']);
        return $response;
    }

    public function callBack($request)
    {
        event(new CallBackEvent($request));
    }

    public function validate()
    {
        if(request()->has(self::VALIDATION)){
            return $this;
        }

        throw new \Exception("You Have Messing Parameters");

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
}
