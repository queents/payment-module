<?php

namespace Modules\Payment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Modules\Payment\Entities\PaymentMethodIntegration;

class PaymentIntegrationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = [
            [
                'payment_method_id' => 2,
                'key' => 'class_name',
                'value' => 'Modules\Payment\Http\Services\FawryPlusPaymentService'
            ],
            [
                'payment_method_id' => 2,
                'key' => 'testing_uri',
                'value' => 'https://atfawry.fawrystaging.com/fawrypay-api/api/payments/init'
            ],
            [
                'payment_method_id' => 2,
                'key' => 'FAWRY_PR_URI',
                'value' => 'https://atfawry.fawrystaging.com/fawrypay-api/api/payments/init'
            ],
            [
                'payment_method_id' => 2,
                'key' => 'merchant_code',
                'value' => 'siYxylRjSPwyUDLWDo/Dsw=='
            ],
            [
                'payment_method_id' => 2,
                'key' => 'security_key',
                'value' => '3210548d-5d93-453d-8dba-a1bdc5c2eb11'
            ],
            [
                'payment_method_id' => 1,
                'key' => 'class_name',
                'value' => 'Modules\Payment\Http\Services\FawryPlusPaymentService'
            ],
            [
                'payment_method_id' => 1,
                'key' => 'testing_uri',
                'value' => 'https://atfawry.fawrystaging.com/fawrypay-api/api/payments/init'
            ],
            [
                'payment_method_id' => 1,
                'key' => 'FAWRY_PR_URI',
                'value' => 'https://atfawry.fawrystaging.com/fawrypay-api/api/payments/init'
            ],
            [
                'payment_method_id' => 1,
                'key' => 'merchant_code',
                'value' => 'siYxylRjSPwyUDLWDo/Dsw=='
            ],
            [
                'payment_method_id' => 1,
                'key' => 'security_key',
                'value' => '3210548d-5d93-453d-8dba-a1bdc5c2eb11'
            ],
            //paymob
            [
                'payment_method_id' => 3,
                'key' => 'class_name',
                'value' => 'Modules\Payment\Http\Services\PaymobPaymentService'
            ],
            [
                'payment_method_id' => 3,
                'key' => 'testing_uri',
                'value' => 'https://accept.paymobsolutions.com/api/acceptance/post_pay'
            ],
            [
                'payment_method_id' => 3,
                'key' => 'PAYMOB_API_KEY',
                'value' => 'ZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6VXhNaUo5LmV5SndjbTltYVd4bFgzQnJJam95TWpreE16RXNJbU5zWVhOeklqb2lUV1Z5WTJoaGJuUWlMQ0p1WVcxbElqb2lhVzVwZEdsaGJDSjkuM1EyeFNFYmx3b2JCSE9KaE1PZmJwWVhyWklhRjFaREYwQnJFWVJDM292TnBXREpBVVBUUWdSaWs1MF80N0k5WkQ5R3U4NTdrNGxmeEhieGlWQUlYNHc=='
            ],
            [
                'payment_method_id' => 3,
                'key' => 'PAYMOB_SANDBOX_INTEGRATION_ID',
                'value' => '2350674'
            ],
            [
                'payment_method_id' => 3,
                'key' => 'PAYMOB_LIVE_INTEGRATION_ID',
                'value' => '2350674'
            ],
            [
                'payment_method_id' => 3,
                'key' => 'PAYMOB_IFRAME_ID',
                'value' => '420271'
            ],
            //pay tabs
            [
                'payment_method_id' => 4,
                'key' => 'merchant_email',
                'value' => 'khaled.abodaif@yahoo.com'
            ],
            [
                'payment_method_id' => 4,
                'key' => 'secret_key',
                'value' => 'SRJN6BMLMR-JG2KJ9WLJH-MZDWNMBLHJ'
            ],
            [
                'payment_method_id' => 4,
                'key' => 'profile_id',
                'value' => '103540'
            ],
            [
                'payment_method_id' => 4,
                'key' => 'class_name',
                'value' => 'Modules\Payment\Http\Services\PayTabsPaymentService'
            ],
        ];

        foreach ($rows as $item) {
            $paymentMethodIng = PaymentMethodIntegration::where('key', $item['key'])
                ->where('payment_method_id', $item['payment_method_id']);
            if ($paymentMethodIng->count()) {
                $paymentMethodIng->update($item);
            } else {
                PaymentMethodIntegration::create($item);
            }
        }
    }
}
