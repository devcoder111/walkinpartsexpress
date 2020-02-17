<?php

namespace App\Providers;

use App\Services\TaxInterface;
use App\Services\TaxService;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TaxInterface::class, TaxService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if(env('REDIRECT_HTTPS'))
        {
            $url->forceScheme('https');
        }

        // Directive to convert a float to currency.
        Blade::directive('convertToCurrency', function ($amount) {
            return "<?php echo '$' . number_format($amount, 2); ?>";
        });
    }
}


