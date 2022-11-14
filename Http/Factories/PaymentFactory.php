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
     * array consist of name of method and object from payment interface class
     * @param string $name
     * @param IPaymentInterface $instance
     * @return PaymentFactory
     */
    public function register (string $name, IPaymentInterface $instance):self
    {
        $this->gateways[$name] = $instance;
        return $this;
    }

    /**
     *
     * get the payment class that the user want
     * if not exist return ex
     * @param string $name
     * @return IPaymentInterface|Exception
     * @throws Exception
     */

    public function get(string $name):IPaymentInterface|Exception
    {
        if (array_key_exists($name, $this->gateways)) {
            return $this->gateways[$name];
        }

        throw new \RuntimeException("Invalid gateway");
    }

    /**
    *
     * get payment methods from table
     * register payment class into gateway array
     * set integration recorder to the class constructor
     */
    public function fillData(): void
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
