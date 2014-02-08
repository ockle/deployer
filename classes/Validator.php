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

	/**
	 * General information calculated during validation that can be accessed
	 * outside the validator, thereby negating the need to recalculate it
	 *
	 * @var array
	 */
	public $information = array();

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

	public function validateRepository($attribute, $value, $parameters)
	{
		$urlComponents = parse_url($value);

		foreach ($this->app['config']['hosts'] as $hostName => $hostClass) {
			if ($hostClass::domainName() == $urlComponents['host']) {
				$this->information['repositoryHostName'] = $hostName;

				return true;
			}
		}

		return false;
	}
}
