<?php


namespace Modules\Payment\Facades;
use Illuminate\Support\Facades\Facade;


class PaymentFacade extends Facade
{

    protected static function getFacadeAccessor() { return 'Payment'; }

}
