<?php

namespace Deployer\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Illuminate\Validation\Validator;
use Illuminate\Validation\DatabasePresenceVerifier;

class ValidatorServiceProvider implements ServiceProviderInterface
{
    /**
     * Register the Validator service.
     *
     * @param Silex\Application $app
     **/
    public function register(Application $app)
    {
        if (isset($app['capsule'])) {
            $app['validator.presence_verifier'] = $app->share(function() use ($app) {
                return new DatabasePresenceVerifier($app['capsule']->manager);
            });
        }

        $app['validator'] = $app->protect(function($data, $rules, $messages = array(), $customAttributes = array()) use ($app) {
            $validator = new Validator($app['translator'], $data, $rules, $messages, $customAttributes);

            if (isset($app['validator.presence_verifier'])) {
                $validator->setPresenceVerifier($app['validator.presence_verifier']);
            }

            return $validator;
        });
    }

    /**
     * Boot the Validator service.
     *
     * @param Silex\Application $app;
     **/
    public function boot(Application $app)
    {
        $app->before(function() use($app) {
            $app['validator'];
        }, Application::EARLY_EVENT);
    }
}