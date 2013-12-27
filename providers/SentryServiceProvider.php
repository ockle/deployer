<?php

namespace Deployer\ServiceProviders;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Cartalyst\Sentry\Hashing\NativeHasher;
use Cartalyst\Sentry\Users\Eloquent\Provider as UserProvider;
use Cartalyst\Sentry\Groups\Eloquent\Provider as GroupProvider;
use Cartalyst\Sentry\Throttling\Eloquent\Provider as ThrottleProvider;
use Cartalyst\Sentry\Sessions\NativeSession;
use Cartalyst\Sentry\Cookies\NativeCookie;
use Cartalyst\Sentry\Sentry;

class SentryServiceProvider implements ServiceProviderInterface
{
    /**
     * Register the Sentry service.
     *
     * @param Silex\Application $app
     **/
    public function register(Application $app)
    {
        $app['sentry.hasher'] = $app->share(function() {
            return new NativeHasher;
        });

        $app['sentry.user_provider'] = $app->share(function() use ($app) {
            return new UserProvider($app['sentry.hasher'], isset($app['sentry.providers']['user']) ? $app['sentry.providers']['user'] : null);
        });

        $app['sentry.group_provider'] = $app->share(function() use ($app) {
            return new GroupProvider(isset($app['sentry.providers']['group']) ? $app['sentry.providers']['group'] : null);
        });

        $app['sentry.throttle_provider'] = $app->share(function() use ($app) {
            return new ThrottleProvider($app['sentry.user_provider'], isset($app['sentry.providers']['throttle']) ? $app['sentry.providers']['throttle'] : null);
        });

        $app['sentry.session'] = $app->share(function() {
            return new NativeSession(isset($app['sentry.session_key']) ? $app['sentry.session_key'] : null);
        });

        $app['sentry.cookie'] = $app->share(function() use ($app) {
            return new NativeCookie(isset($app['sentry.cookie_settings']) ? $app['sentry.cookie_settings'] : array(), isset($app['sentry.cookie_key']) ? $app['sentry.cookie_key'] : null);
        });

        $app['sentry'] = $app->share(function($app) {
            $sentry = new Sentry($app['sentry.user_provider'], $app['sentry.group_provider'], $app['sentry.throttle_provider'], $app['sentry.session'], $app['sentry.cookie']);

            return $sentry;
        });
    }

    /**
     * Boot the Sentry service.
     *
     * @param Silex\Application $app;
     **/
    public function boot(Application $app)
    {
        $app->before(function() use($app) {
            $app['sentry'];
        }, Application::EARLY_EVENT);
    }
}