<?php

namespace App\Providers;

use App\Cart;
use App\WebCategory;
use App\SocialMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewShareServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {   // Share the following with all views (wildcard allowed below)
        View::composer('*', function ($view) {
            $categories = WebCategory::orderBy('name', 'ASC')->get();
            $socialMedias = SocialMedia::orderBy('id', 'ASC')->get();

            foreach ($categories as &$category) {
                try {
                    $category->image_id = WebCategory::with([
                        'products.images',
                        'products' => function ($query) {
                            $query->has('images');
                        }
                    ])
                        ->find((int)$category->id)
                        ->products
                        ->first()
                        ->images
                        ->first()
                        ->id;
                } catch (\Exception $e) {
                    $category->image_id = null;
                }
            }

            //social media data

            $view->with('categories', $categories)
                 ->with('socialMedia', $socialMedias);
        });

        // Directive to convert a float to currency.
        Blade::directive('convertToCurrency', function ($amount) {
            return "<?php echo '$' . number_format($amount, 2); ?>";
        });
    }
}


