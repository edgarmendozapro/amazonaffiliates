<?php

namespace EdgarMendozaTech\AmazonAffiliates;

use Illuminate\Support\ServiceProvider;

class AmazonAffiliatesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerRouteMacro();
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function registerRouteMacro()
    {
        $router = $this->app['router'];
        $router->macro('amazonAffiliates', function ($baseUrl = '') use ($router) {
            $router->group(
                ['prefix' => 'amazon-afiliados', 'namespace' => '\EdgarMendozaTech\AmazonAffiliates\Http\Controllers'], function() use ($router) {
                    $router->group(
                        ['prefix' => 'tiendas'],
                        function () use ($router) {
                            $router->get('/', 'AmazonStoreController@index');
                            $router->get('/editar/{store}', 'AmazonStoreController@edit');
                            $router->put(
                                '/editar/{store}',
                                'AmazonStoreController@update'
                            );
                        }
                    );

                    $router->group(['prefix' => 'enlaces'], function () use (
                        $router
                    ) {
                        $router->get('/', 'AmazonLinkController@index');
                        $router->get('/lista', 'AmazonLinkController@list');
                        $router->post('/crear', 'AmazonLinkController@store');
                        $router->get('/editar/{link}', 'AmazonLinkController@edit');
                        $router->put('/editar/{link}', 'AmazonLinkController@update');
                        $router->delete(
                            '/eliminar/{link}',
                            'AmazonLinkController@destroy'
                        );
                    });
            });
        });
    }
}
