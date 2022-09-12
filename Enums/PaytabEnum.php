<?php

namespace Modules\Payment\Enums;

use Modules\Base\Supports\Enum;

class PaytabEnum extends Enum
{
    public const PAYTABS_DOMAIN = "https://secure-egypt.paytabs.com/payment/request";
    public const PAYTABS_TYPE = "sale";
    public const PAYTABS_CLASS = "ecom";
    public const PAYTABS_CLLBACK = "https://viltbreeze.test/callback/payment/4";
    public const VALIDATION=[
        "order_id"=>'required|numeric',
        "order_table"=>'required|string',
        "paymentMethodId"=>'required|integer',
        "amount"=>'required|numeric',
        "notes"=>'nullable',
        "returnUrl"=>'required|string',
    ];
}
