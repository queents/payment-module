<?php

namespace Modules\Payment\Enums;

use Modules\Base\Supports\Enum;

class PaymobEnum extends Enum
{
    public const PAYMOB_DOMAIN = "https://accept.paymobsolutions.com/api/";
    public const IFRAME_REQUEST = "acceptance/iframes/";
    public const TOKENS_REQUEST = "auth/tokens";
    public const ORDER_REQUEST = "ecommerce/orders";
    public const PAYMENT_TOKEN_REQUEST = "acceptance/payment_keys";
    const VALIDATION =
        [
            "order_id" => 'required|numeric',
            "order_table" => 'required|string',
            "paymentMethodId" => 'required|integer',
            "amount" => 'required|numeric',
            "notes" => 'nullable',
            "first_name" => 'required|string',
            "last_name" => 'required|string',
            "phone" => 'required|string'
        ];
}
