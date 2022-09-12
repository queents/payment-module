<?php


namespace Modules\Payment\Http\Interfaces;


interface IPaymobInterface
{

    public function authenticationRequest(): mixed;

    public function orderRegistrationAPI(string $token): mixed;

    public function PaymentKeyRequest(mixed $order, string $token): mixed;
}
