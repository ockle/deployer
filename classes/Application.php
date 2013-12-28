<?php

namespace Deployer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Application extends \Silex\Application
{
    // Manually including the UrlGeneratorTrait because I want/need the 
    // application to run under PHP 5.3, but still want the convenience

    /**
     * Generates a path from the given parameters.
     *
     * @param string $route      The name of the route
     * @param mixed  $parameters An array of parameters
     *
     * @return string The generated path
     */
    public function path($route, $parameters = array())
    {
        return $this['url_generator']->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    /**
     * Generates an absolute URL from the given parameters.
     *
     * @param string $route      The name of the route
     * @param mixed  $parameters An array of parameters
     *
     * @return string The generated URL
     */
    public function url($route, $parameters = array())
    {
        return $this['url_generator']->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function redirectWithData($path, array $data)
    {
        $this['session']->getFlashBag()->set('redirectData', $data);

        $subRequest = Request::create($path, 'GET');

        return $this->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }

    public function getRedirectData()
    {
        return $this['session']->getFlashBag()->get('redirectData');
    }
}
