<?php


namespace Modules\Payment\Vilt\Resources;

use Modules\Payment\Entities\PaymentMethod;
use Modules\Base\Services\Resource\Resource;
use Modules\Payment\Vilt\Resources\PaymentMethodResource\Traits\Translations;
use Modules\Payment\Vilt\Resources\PaymentMethodResource\Traits\Components;

use Modules\Base\Services\Rows\Text;
    use Modules\Base\Services\Rows\Schema;
    use Modules\Base\Services\Rows\Color;
    use Modules\Base\Services\Rows\Toggle;


class PaymentMethodResource extends Resource
{
        use Translations,Components;

        public ?string $model = PaymentMethod::class;
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

                    Schema::make('name')
                        ->label(__('name'))
                        ->validation([
                            "create" => "required|array",
                            "update" => "required|array"
                        ])
                        ->options([]),

                    Schema::make('description')
                        ->label(__('description'))
                        ->validation([
                            "create" => "nullable|array",
                            "update" => "nullable|array"
                        ])
                        ->options([]),

                    Color::make('color')
                        ->label(__('color'))
                        ->validation([
                            "create" => "nullable|string|max:255",
                            "update" => "nullable|string|max:255"
                        ]),

                    Text::make('icon')
                        ->label(__('icon'))
                        ->validation([
                            "create" => "nullable|string|max:255",
                            "update" => "nullable|string|max:255"
                        ]),

                    Toggle::make('activated')
                        ->label(__('activated'))
                        ->validation([
                            "create" => "required|bool",
                            "update" => "required|bool"
                        ])
                        ->default("1"),
            ];

        }

}
