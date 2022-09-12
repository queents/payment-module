<?php


namespace Modules\Payment\Enums;


class FawryEnum
{
    const VALIDATION=
        [
            "order_id"=>'required|numeric',
            "order_table"=>'required|string',
            "paymentMethodId"=>'required|integer',
            "amount"=>'required|numeric',
            "notes"=>'nullable',
            "returnUrl"=>'required|string',
            "chargeItems"=>'required|array'
        ];

    const PAYMENT_METHODS=
        [
            "2" => "PayAtFawry",
            "1" => "CARD"
        ];
}
