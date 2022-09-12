<?php

namespace Modules\Payment\Http\Factories;

use Exception;
use Illuminate\Support\Facades\Cache;
use Modules\Payment\Entities\PaymentMethod;
use Modules\Payment\Http\Interfaces\IPaymentInterface;
use Modules\Payment\Http\Services\FawryPlusPaymentService;

/**
 *
 * this class choose and register payment methods that provided in service provider
 *
 */
class PaymentFactory{


    public array $gateways = [];

    /**
     *
     * register array of payment methods that get it from service provider
     * array consist of name of method and object from payment interface calss
     * @param string $name
     * @param IPaymentInterface $instance
     * @return PaymentFactory
     */
    function register (string $name, IPaymentInterface $instance):self
    {
        $this->gateways[$name] = $instance;
        return $this;
    }

    /**
     *
     * get the payment class that the user want
     * if not exist return ex
     * @param string $name
     * @return IPaymentInterface
     */

    function get(string $name):IPaymentInterface|Exception
    {
        if (array_key_exists($name, $this->gateways)) {
            return $this->gateways[$name];
        } else {
            throw new Exception("Invalid gateway");
        }
    }

    /**
    *
     * get payment methods from table
     * register payment class into getways array
     * set integration recorder to the class constructor
     */
    public function fillData()
    {

        $paymentMethods = PaymentMethod::select('id')->where('activated', 1)->with('paymentMethodIntegrations')->get();

        foreach ($paymentMethods as $method) {

            if (count($method->paymentMethodIntegrations) > 0) {
               $className= $method->paymentMethodIntegrations->where('key','class_name')->value('value');
                $this->register($method->id, new $className($method->paymentMethodIntegrations));

            }
        }
    }

}
