<?php

namespace Modules\Payment\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Http\Helpers\ApiResponse;
use Modules\Payment\Http\Factories\PaymentFactory;
use Modules\Payment\Http\Helpers\PaymentSaveToLogs;
use Modules\Payment\Http\Requests\PaymobRequest;

/*
    this class for test the service act as example for how to use this package
*/

class PaymentController extends Controller
{
    use PaymentSaveToLogs;

    private $payment;

    /**
     * this constructor get an object from factory class that
     * get an instance from payment methods you can find
     * it's registration in payment service provider
     * @param PaymentFactory $payment
     */
    public function __construct(PaymentFactory $payment)
    {
        $this->payment = $payment;
        $this->payment->fillData();

    }


    /**
     * |try if the payment method is invalid will fire ex
     * |and return the ex message "invalid getway "
     *
     *
     * | try the payment method  if any ex happen from payment side
     * | will retrun status false and the ex message
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */

    public function paymentMethod(Request $request)
    {

        try {
            $result = $this->payment->get(request()->get('paymentMethodId'))
                ->validate()
                ->saveToPayment()
                ->init($request)
                ->pay();
        } catch (\Exception $e) {

            $this->saveToLogs($request->all(),$e->getMessage());
            return ApiResponse::errors($e->getMessage(), 400);
        }


        if (!$result['status']){
            $this->saveToLogs($request->all(),$result);
            return ApiResponse::errors($result['message'], 500);

        }

        return  ApiResponse::data($result['data'], "done", 200);
    }


    /**
     * @param Request $request
     * @param int $paymentMethod
     */
    public function paymentCallback(Request $request, int $paymentMethod)
    {

        try {
            $this->payment->get($paymentMethod)->callBack($request->all());
        } catch (\Exception $e) {

            $this->saveToLogs(array_merge($request->all(),["paymentMethod" => $paymentMethod]),$e->getMessage());

        }
    }

}
