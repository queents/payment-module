<?php


namespace Modules\Payment\Vilt\Resources\PaymentResource\Traits;

use Modules\Base\Services\Components\Base\Component;


trait Components
{
    public function components():array
    {
        $components = parent::components();
        return array_merge($components, [

        ]);
    }
}
