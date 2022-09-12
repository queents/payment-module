<?php

namespace Modules\Payment\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Modules\Payment\Http\Services\PaymentService;

class PaymentFacadeServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        App::bind('Payment',function() {
            return new PaymentService();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {

        return [];
    }
}
