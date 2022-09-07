<?php


namespace Modules\Payment\Vilt\Resources;

use Modules\Payment\Entities\PaymentStatus;
use Modules\Base\Services\Resource\Resource;
use Modules\Payment\Vilt\Resources\PaymentStatusResource\Traits\Translations;
use Modules\Payment\Vilt\Resources\PaymentStatusResource\Traits\Components;

use Modules\Base\Services\Rows\Text;
    use Modules\Base\Services\Rows\Schema;
    use Modules\Base\Services\Rows\Color;


class PaymentStatusResource extends Resource
{
        use Translations,Components;

        public ?string $model = PaymentStatus::class;
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
            ];

        }

}
