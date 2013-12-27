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
        $app['blade'] = $app->share(function($app) {
            $filesystem = new Filesystem;
            $compiler = new BladeCompiler($filesystem, $app['blade.settings']['cache']);
            $resolver = new EngineResolver;
            $resolver->register('blade', function() use ($compiler, $filesystem) {
                return new CompilerEngine($compiler, $filesystem);
            });
            $finder = new FileViewFinder($filesystem, $app['blade.settings']['views']);
            $events = new Dispatcher;

            return new Environment($resolver, $finder, $events);
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