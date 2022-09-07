<?php


namespace Modules\Payment\Http\Helpers;


use Modules\Payment\Entities\PaymentLog;

trait PaymentSaveToLogs
{

    public function saveToLogs(mixed $payload, mixed $response)
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
