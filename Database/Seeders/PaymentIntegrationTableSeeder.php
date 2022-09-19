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
                'value' => ''
            ],
            [
                'payment_method_id' => 2,
                'key' => 'security_key',
                'value' => ''
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
                'value' => ''
            ],
            [
                'payment_method_id' => 1,
                'key' => 'security_key',
                'value' => ''
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
                'value' => ''
            ],
            [
                'payment_method_id' => 3,
                'key' => 'PAYMOB_SANDBOX_INTEGRATION_ID',
                'value' => ''
            ],
            [
                'payment_method_id' => 3,
                'key' => 'PAYMOB_LIVE_INTEGRATION_ID',
                'value' => ''
            ],
            [
                'payment_method_id' => 3,
                'key' => 'PAYMOB_IFRAME_ID',
                'value' => ''
            ],
            //pay tabs
            [
                'payment_method_id' => 4,
                'key' => 'merchant_email',
                'value' => ''
            ],
            [
                'payment_method_id' => 4,
                'key' => 'secret_key',
                'value' => ''
            ],
            [
                'payment_method_id' => 4,
                'key' => 'profile_id',
                'value' => ''
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
