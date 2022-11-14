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
    use Translations, Components;

    public ?string $model = PaymentMethod::class;
    public ?string $icon = "bx bxl-stripe";
    public ?string $group = "Payment";
    public ?bool $api = true;

    public function rows(): array
    {
        $locals = [];
        foreach (config('translations.locals') as $key=>$local){
            $locals[] = Text::make($key)->label($local);
        }
        return [
            Text::make('id')
                ->label(__('ID'))
                ->create(false)
                ->edit(false),

            Schema::make('name')
                ->label(__('Name'))
                ->validation([
                    "create" => "required|array",
                    "update" => "required|array"
                ])
                ->options($locals),

            Schema::make('description')
                ->label(__('Description'))
                ->validation([
                    "create" => "nullable|array",
                    "update" => "nullable|array"
                ])
                ->options($locals),

            Color::make('color')
                ->label(__('Color'))
                ->validation([
                    "create" => "nullable|string|max:255",
                    "update" => "nullable|string|max:255"
                ]),

            Text::make('icon')
                ->label(__('Icon'))
                ->validation([
                    "create" => "nullable|string|max:255",
                    "update" => "nullable|string|max:255"
                ]),

            Toggle::make('activated')
                ->label(__('Activated'))
                ->validation([
                    "create" => "required|bool",
                    "update" => "required|bool"
                ])
                ->default("1"),
        ];
    }
}
