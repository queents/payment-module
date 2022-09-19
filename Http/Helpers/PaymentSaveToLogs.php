<?php


namespace Modules\Payment\Http\Helpers;


use Modules\Payment\Entities\PaymentLog;

trait PaymentSaveToLogs
{
    /**
     * @param mixed $payload
     * @param mixed $response
     * @return void
     */
    public function saveToLogs(mixed $payload, mixed $response): void
    {
        PaymentLog::create(
            [
                "status" => false,
                "payload" => json_encode($payload),
                "response" => json_encode($response),
            ]
        );
    }
}
