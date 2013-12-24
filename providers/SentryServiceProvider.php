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
        $app['sentry'] = $app->share(function($app) {
            $hasher = new NativeHasher;
            $userProvider = new UserProvider($hasher, isset($app['sentry.providers']['user']) ? $app['sentry.providers']['user'] : null);
            $groupProvider = new GroupProvider(isset($app['sentry.providers']['group']) ? $app['sentry.providers']['group'] : null);
            $throttleProvider = new ThrottleProvider($userProvider, isset($app['sentry.providers']['throttle']) ? $app['sentry.providers']['throttle'] : null);
            $session = new NativeSession;
            $cookie = new NativeCookie(isset($app['sentry.cookie']) ? $app['sentry.cookie'] : array());

            $sentry = new Sentry(
                $userProvider,
                $groupProvider,
                $throttleProvider,
                $session,
                $cookie
            );

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