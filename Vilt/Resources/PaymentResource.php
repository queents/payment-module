<?php


namespace Modules\Payment\Vilt\Resources;

use Modules\Payment\Entities\Payment;
use Modules\Base\Services\Resource\Resource;
use Modules\Payment\Vilt\Resources\PaymentResource\Traits\Translations;
use Modules\Payment\Vilt\Resources\PaymentResource\Traits\Components;

use Modules\Base\Services\Rows\Text;
    use Modules\Base\Services\Rows\HasOne;
    use Modules\Base\Services\Rows\Number;


class PaymentResource extends Resource
{
        use Translations,Components;

        public ?string $model = Payment::class;
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
                            "create" => "nullable|array|max:0",
                            "update" => "nullable|array|max:0"
                        ])
                        ->model(\Modules\Payment\Entities\PaymentMethod::class)
                        ->relation("paymentMethod"),

                    HasOne::make('payment_status_id')
                        ->label(__('payment status id'))
                        ->validation([
                            "create" => "required|array|max:0",
                            "update" => "required|array|max:0"
                        ])
                        ->model(\Modules\Payment\Entities\PaymentStatus::class)
                        ->relation("paymentStatus"),

                    Number::make('model_id')
                        ->label(__('model id'))
                        ->validation([
                            "create" => "required|integer",
                            "update" => "required|integer"
                        ]),

                    Text::make('model_table')
                        ->label(__('model table'))
                        ->validation([
                            "create" => "required|string|max:255",
                            "update" => "required|string|max:255"
                        ]),

                    Text::make('transaction_code')
                        ->label(__('transaction code'))
                        ->validation([
                            "create" => "required|string|max:255",
                            "update" => "required|string|max:255"
                        ])
                        ->unique(),

                    Number::make('amount')
                        ->label(__('amount'))
                        ->validation([
                            "create" => "required|integer",
                            "update" => "required|integer"
                        ]),

                    Text::make('notes')
                        ->label(__('notes'))
                        ->validation([
                            "create" => "nullable|string|max:255",
                            "update" => "nullable|string|max:255"
                        ]),
            ];

        }

}
