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
    use Translations, Components;

    public ?string $model = PaymentStatus::class;
    public ?string $icon = "bx bxs-check-circle";
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
                ->list(false)
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
        ];
    }
}
