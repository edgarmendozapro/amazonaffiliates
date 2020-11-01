<?php

namespace Kiusoft\Admin;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerRouteMacro();
    }

    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/../package.json' => base_path('package.json'),
            ],
            'admin_js_dependencies'
        );

        $this->publishes(
            [
                __DIR__ . '/../webpack.mix.js' => base_path('webpack.mix.js'),
            ],
            'admin_webpack'
        );

        $this->publishes(
            [
                __DIR__ . '/../public/js' => public_path('js'),
                __DIR__ . '/../public/images' => public_path('images'),
                __DIR__ . '/../public/css' => public_path('css'),
            ],
            'admin_public_assets'
        );

        $this->publishes(
            [
                __DIR__ . '/../resources/assets' => resource_path(
                    'assets/admin'
                ),
            ],
            'admin_assets'
        );

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
                __DIR__.'/../database/seeds' => database_path('seeds'),
            ], 'admin_seeds');

        $this->publishes(
            [
                __DIR__ .
                '/../resources/views/index.blade.php' => resource_path(
                    'views/admin/index.blade.php'
                ),
            ],
            'admin_view'
        );

        $this->publishes(
            [
                __DIR__ . '/../config/admin.php' => config_path('admin.php'),
            ],
            'admin_config'
        );
    }

    protected function registerRouteMacro()
    {
        $router = $this->app['router'];
        $router->macro('admin', function ($baseUrl = '') use ($router) {
            $router->group(
                ['namespace' => '\Kiusoft\Admin\Http\Controllers'], function() use ($router) {
                $router
                    ->get('/', 'AdminController@index')
                    ->name('admin.index');

                $router->get('/contadores', 'StatsController@counters');
                $router->get(
                    '/stats-visualizaciones',
                    'StatsController@visualizationStats'
                );

                $router
                    ->post(
                        'recurso-grafico',
                        'MediaResourceController@store'
                    )
                    ->name('media_resources.store');

                $router->group(
                    [
                        'prefix' => 'blog',
                    ],
                    function () use ($router) {
                        $router->group(
                            ['prefix' => 'publicaciones'],
                            function () use ($router) {
                                $router->get('/', 'PostController@index');
                                $router->get(
                                    '/datos-secundarios',
                                    'PostController@secondaryData'
                                );
                                $router->post('/crear', 'PostController@store');
                                $router->get(
                                    '/editar/{post}',
                                    'PostController@edit'
                                );
                                $router->put(
                                    '/editar/{post}',
                                    'PostController@update'
                                );
                                $router->delete(
                                    '/eliminar/{post}',
                                    'PostController@destroy'
                                );
                            }
                        );

                        $router->group(['prefix' => 'etiquetas'], function () use (
                            $router
                        ) {
                            $router->get('/', 'TagController@index');
                            $router->post('/crear', 'TagController@store');
                            $router->get('/editar/{tag}', 'TagController@edit');
                            $router->put('/editar/{tag}', 'TagController@update');
                            $router->delete(
                                '/eliminar/{tag}',
                                'TagController@destroy'
                            );
                        });

                        $router->group(['prefix' => 'categorias'], function () use (
                            $router
                        ) {
                            $router->get('/', 'CategoryController@index');
                            $router->get('/lista', 'CategoryController@list');
                            $router->post('/crear', 'CategoryController@store');
                            $router->get(
                                '/editar/{category}',
                                'CategoryController@edit'
                            );
                            $router->put(
                                '/editar/{category}',
                                'CategoryController@update'
                            );
                            $router->delete(
                                '/eliminar/{category}',
                                'CategoryController@destroy'
                            );
                        });

                        $router->group(['prefix' => 'autores'], function () use (
                            $router
                        ) {
                            $router->get('/', 'AuthorController@index');
                            $router->post('/crear', 'AuthorController@store');
                            $router->get(
                                '/editar/{author}',
                                'AuthorController@edit'
                            );
                            $router->put(
                                '/editar/{author}',
                                'AuthorController@update'
                            );
                            $router->delete(
                                '/eliminar/{author}',
                                'AuthorController@destroy'
                            );
                        });
                    }
                );


                $router->group(
                    [
                        'prefix' => 'amazon-afiliados',
                    ],
                    function () use ($router) {
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
                    }
                );
            });
        });
    }
}
