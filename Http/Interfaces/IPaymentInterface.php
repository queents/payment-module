<?php

namespace Modules\Payment\Http\Interfaces;

interface IPaymentInterface
{
    public function init():self;
    public function pay():array;
    public function callBack($request): void;
    public function validate( array $attributes):self;
}
