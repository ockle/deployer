<?php

namespace Deployer\ServiceProviders;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Environment;
use Illuminate\View\FileViewFinder;

class BladeServiceProvider implements ServiceProviderInterface
{
    /**
     * Register the Blade service.
     *
     * @param Silex\Application $app
     **/
    public function register(Application $app)
    {
        $app['blade.filesystem'] = $app->share(function() {
            return new Filesystem;
        });

        $app['blade.compiler'] = $app->share(function() use ($app) {
            return new BladeCompiler($app['blade.filesystem'], $app['blade.settings']['cache']);
        });

        $app['blade.compiler_engine'] = $app->share(function() use ($app) {
            return new CompilerEngine($app['blade.compiler'], $app['blade.filesystem']);
        });

        $app['blade.resolver'] = $app->share(function() {
            return new EngineResolver;
        });

        $app['blade.resolver']->register('blade', function() use ($app) {
            return $app['blade.compiler_engine'];
        });

        $app['blade.finder'] = $app->share(function() use ($app) {
            return new FileViewFinder($app['blade.filesystem'], $app['blade.settings']['views']);
        });

        $app['blade.dispatcher'] = $app->share(function() {
            return new Dispatcher;
        });

        $app['blade'] = $app->share(function($app) {
            return new Environment($app['blade.resolver'], $app['blade.finder'], $app['blade.dispatcher']);
        });
    }

    /**
     * Boot the Blade service.
     *
     * @param Silex\Application $app;
     **/
    public function boot(Application $app)
    {
        $app->before(function() use($app) {
            $app['blade'];
        }, Application::EARLY_EVENT);
    }
}