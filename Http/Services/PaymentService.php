<?php


namespace Modules\Payment\Http\Services;


use Illuminate\Http\Request;
use Modules\Payment\Http\Factories\PaymentFactory;
use Modules\Payment\Http\Helpers\PaymentSaveToLogs;

class PaymentService
{

    use PaymentSaveToLogs;

    private array $response= ['status' => 'false', 'message' => '','data' => ''];
    private $payment;

    /**
     * this constructor get an object from factory class that
     * get an instance from payment methods you can find
     * it's registration in payment service provider
     * @param PaymentFactory $payment
     */
    private function init(PaymentFactory $payment)
    {
        $this->payment = $payment;
        $this->payment->fillData();

    }

    public function pay(array $request):self
    {

        $this->init(new PaymentFactory());
        try {
            $result = $this->payment->get($request['paymentMethodId'])
                ->validate($request)
                ->saveToPayment()
                ->init()
                ->pay();
        } catch (\Exception $e) {

            $this->saveToLogs($request,$e->getMessage());
            $this->response['message']= $e->getMessage();
            return  $this;

        }


        if (!$result['status']){
            $this->saveToLogs($request,$result);
            $this->response['message']= $result['message'];
            return  $this;
        }

        $this->response=$result;
        return  $this;

    }


    public function getErrorMessage()
    {
        return $this->response['message'];
    }

    public function getData()
    {
        return $this->response['data'];
    }

    /**
     * @param Request $r    equest
     * @param int $paymentMethod
     */
    public function callback(Request $request, int $paymentMethod)
    {
        $this->init(new PaymentFactory());

        try {
            $this->payment->get($paymentMethod)->callBack($request->all());
        } catch (\Exception $e) {

            $this->saveToLogs(array_merge($request->all(),["paymentMethod" => $paymentMethod]),$e->getMessage());

        }
    }

    public function errors(){

    }

    public function testingFacades() {
        echo "Testing the Facades in Laravel.";
    }



}
