<?php

namespace Deployer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Application extends \Silex\Application
{
    protected $redirectData;

    // Manually including the UrlGeneratorTrait because I require the application
    // to run under PHP 5.3, but still want the convenience of the short methods

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
        return (($this['request']->getBasePath() != $this['basePath']) ? $this['basePath'] : '') . $this['url_generator']->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);
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

    public function redirect($route, $data = array(), $status = 302)
    {
        $this->setRedirectData($data);

        return parent::redirect($this->path($route), $status);
    }

    public function forward($route, $data = array())
    {
        $this->setRedirectData($data);

        $subRequest = Request::create($this->routeToPath($route), 'GET');

        // $subRequest->baseUrl = $this['request']->baseUrl;
        // $subRequest->requestUri = $this['request']->requestUri;

        return $this->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }

    protected function setRedirectData(array $data)
    {
        $this['session']->getFlashBag()->set('redirectData', $data);

        return $this;
    }

    protected function routeToPath($route)
    {
        if (is_array($route)) {
            $routeName       = $route[0];
            $routeParameters = $route[1];
        } else {
            $routeName       = $route;
            $routeParameters = array();
        }

        return str_replace($this['request']->getBaseUrl(), '', $this->path($routeName, $routeParameters));
    }

    public function getRedirectData()
    {
        if (!isset($this->redirectData)) {
            $this->redirectData = $this['session']->getFlashBag()->get('redirectData');
        }

        return $this->redirectData;
    }

    public function oldValue($name)
    {
        $redirectData = $this->getRedirectData();

        return (isset($redirectData['oldInput'][$name])) ? $redirectData['oldInput'][$name] : false;
    }
}
