<?php


namespace Modules\Payment\Vilt\Resources;

use Modules\Payment\Entities\PaymentMethodIntegration;
use Modules\Base\Services\Resource\Resource;
use Modules\Payment\Vilt\Resources\PaymentMethodIntegrationResource\Traits\Translations;
use Modules\Payment\Vilt\Resources\PaymentMethodIntegrationResource\Traits\Components;

use Modules\Base\Services\Rows\Text;
    use Modules\Base\Services\Rows\HasOne;


class PaymentMethodIntegrationResource extends Resource
{
        use Translations,Components;

        public ?string $model = PaymentMethodIntegration::class;
        public ?string $icon = "bx bxs-circle";
        public ?string $group = "Payment";
        public ?bool $api = true;

        public function rows():array
        {
            return [


                    Text::make('id')
                        ->label(__('id'))
                        ->create(false)
                        ->edit(false),

                    HasOne::make('payment_method_id')
                        ->label(__('payment method id'))
                        ->validation([
                            "create" => "required|array|max:0",
                            "update" => "required|array|max:0"
                        ])
                        ->model(\Modules\Payment\Entities\PaymentMethod::class)
                        ->relation("paymentMethod"),

                    Text::make('key')
                        ->label(__('key'))
                        ->validation([
                            "create" => "required|string|max:255",
                            "update" => "required|string|max:255"
                        ]),

                    Text::make('value')
                        ->label(__('value'))
                        ->validation([
                            "create" => "required|string|max:255",
                            "update" => "required|string|max:255"
                        ]),
            ];

        }

}
