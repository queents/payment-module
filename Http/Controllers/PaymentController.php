<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Http\Helpers\ApiResponse;
use Modules\Payment\Http\Factories\PaymentFactory;
use Modules\Payment\Http\Helpers\PaymentSaveToLogs;
use Modules\Payment\Http\Services\PaymentService;

/*
    this class for test the service act as example for how to use this package
*/

class PaymentController extends Controller
{
    private $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService=$paymentService;
    }

    /**
     * |try if the payment method is invalid will fire ex
     * |and return the ex message "invalid gateway"
     *
     *
     * | try the payment method  if any ex happen from payment side
     * | will return status false and the ex message
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function paymentMethod(Request $request)
    {
        $this->paymentService->pay($request->all());
//        $payment=\PaymentModule::pay($request->all());

        if ($this->paymentService->getErrorMessage()) {
            return ApiResponse::errors($this->paymentService->getErrorMessage(), 400);
        }

        return  ApiResponse::data($this->paymentService->getData(), "done", 200);
    }


    /**
     * @param Request $request
     * @param int $paymentMethod
     */
    public function paymentCallback(Request $request, int $paymentMethod): void
    {
        $this->paymentService->callback($request->all(),$paymentMethod);
    }


}
