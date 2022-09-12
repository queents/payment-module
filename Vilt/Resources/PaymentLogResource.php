<?php


namespace Modules\Payment\Vilt\Resources;

use Modules\Payment\Entities\PaymentLog;
use Modules\Base\Services\Resource\Resource;
use Modules\Payment\Vilt\Resources\PaymentLogResource\Traits\Translations;
use Modules\Payment\Vilt\Resources\PaymentLogResource\Traits\Components;

use Modules\Base\Services\Rows\Text;
    use Modules\Base\Services\Rows\Toggle;
    use Modules\Base\Services\Rows\Schema;


class PaymentLogResource extends Resource
{
        use Translations,Components;

        public ?string $model = PaymentLog::class;
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

                    Toggle::make('status')
                        ->label(__('status'))
                        ->validation([
                            "create" => "required|bool",
                            "update" => "required|bool"
                        ]),

                    Text::make('payload')
                        ->label(__('payload'))
                        ->validation([
                            "create" => "required|array",
                            "update" => "required|array"
                        ])
                        ->options([]),

                Text::make('response')
                        ->label(__('response'))
                        ->validation([
                            "create" => "required|array",
                            "update" => "required|array"
                        ])
                        ->options([]),
            ];

        }

}
