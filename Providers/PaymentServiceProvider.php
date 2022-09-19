<?php

namespace Modules\Payment\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Base\Services\Core\VILT;
use Modules\Base\Services\Components\Base\Lang;
use Modules\Payment\Console\InstallPayment;
use Modules\Payment\Http\Factories\PaymentFactory;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Payment';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'payment';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        $this->commands([
            InstallPayment::class
        ]);

        VILT::loadResources($this->moduleName);
        VILT::loadPages($this->moduleName);
        VILT::registerTranslation(Lang::make('payments.sidebar')->label(__('Payments')));
        VILT::registerTranslation(Lang::make('payment_status.sidebar')->label(__('Payments Status')));
        VILT::registerTranslation(Lang::make('payment_methods.sidebar')->label(__('Payments Methods')));
        VILT::registerTranslation(Lang::make('payment_logs.sidebar')->label(__('Payments Logs')));
        VILT::registerTranslation(Lang::make('payment_method_integrations.sidebar')->label(__('Payments Integrations')));
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->singleton(PaymentFactory::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
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
