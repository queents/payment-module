<?php


namespace Modules\Payment\Enums;


class CashOnDeliveryEnum
{
    const VALIDATION =
        [
            "order_id" => 'required|numeric',
            "order_table" => 'required|string',
            "paymentMethodId" => 'required|integer',
            "amount" => 'required|numeric',
            "notes" => 'nullable',
            "returnUrl" => 'required|string',
            "chargeItems" => 'required|array',

        ];

}
