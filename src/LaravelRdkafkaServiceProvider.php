<?php

namespace LaravelRdkafka\Rdkafka;

use Illuminate\Support\ServiceProvider;

class LaravelRdkafkaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //todo
        //发布配置文件到config目录
        $this->publishes([
            __DIR__.'/config/rdkafka.php' => config_path('rdkafka.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //todo
        $this->app['rdkafka'] = $this->app->share(function ($app) {
            return new Rdkafka($app['config']['rdkafka']);
        });
        //merge the package config with the user config
//        $this->mergeConfigFrom(
//            __DIR__.'/config/rdkafka.php', 'rdkafka'
//        );
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['rdkafka'];
    }
}
