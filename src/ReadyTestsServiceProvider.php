<?php

namespace Fadaa\ReadyTests;

use Illuminate\Support\ServiceProvider;

class ReadyTestsServiceProvider extends ServiceProvider
{
    public function register()
    {

        // to merge user config over default config
        // تكون قبل
        // bind, singletone
        // لان bind, singletone ممكن تحتاج ملف config
        // $this->mergeConfigFrom(  __DIR__.'/../config/readytests.php', 'readytests');


        $this->app->bind(ReadyTests::class, function () {
          return new ReadyTests();
        });


        $this->app->bind('ReadyTestsService', function () {
            return new \Fadaa\ReadyTests\Services\ReadyTestsService();
        });


        //  ممكن هنا نعمل
        // alias
        // لو مشتغلتش فى
        // composer.json  extra aliasis
        // $this->app->alias(LaravelLocalization::class, 'laravellocalization');
        // $this->app->alias(ReadyTests::class, 'ReadyTests');
        // تسجيل Facade
        // $this->app->alias('ReadyTestsService', \Fadaa\ReadyTests\Services\ReadyTestsService::class);



    }

    public function boot()
    {
        // when run (php artisan migrate) laravel will run this line also
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laraattributes'); // __('readytests::messages.welcome');
        // $this->loadViewsFrom(__DIR__.'/../resources', 'readytests');


        if ($this->app->runningInConsole()) {
            $this->publishes([
              __DIR__.'/../config/ready-tests.php' => config_path('ready-tests.php'),
            ]);

            // $this->publishes([
            //     __DIR__.'/path/to/views' => resource_path('views/vendor/package'),
            // ]);

            // $this->publishes([
            //     __DIR__.'/path/to/lang' => resource_path('lang/vendor/package'),
            // ]);

            // $this->publishes([
            //     __DIR__.'/path/to/assets' => public_path('vendor/package'),
            // ]);
        }

    }
}
