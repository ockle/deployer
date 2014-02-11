<?php

namespace Deployer;

use Symfony\Component\Translation\TranslatorInterface;

class Validator extends \Illuminate\Validation\Validator
{
    /**
     * The Deployer application
     *
     * @var \Deployer\Application
     */
    protected $app;

    public function __construct(Application $app, TranslatorInterface $translator, $data, $rules, $messages = array(), $customAttributes = array())
    {
        $this->app = $app;

        parent::__construct($translator, $data, $rules, $messages, $customAttributes);
    }

    public function validateDirectory($attribute, $value, $parameters)
    {
        return is_dir($value);
    }

    public function validateGit($attribute, $value, $parameters)
    {
        return is_dir(rtrim($value, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '.git');
    }

    public function validateHost($attribute, $value, $parameters)
    {
        return array_key_exists($value, $this->app['config']['hosts']);
    }
}
