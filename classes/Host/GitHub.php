<?php

namespace Deployer\Host;

class GitHub implements HostInterface
{
	public $payload;

	public function __construct(array $payload)
	{
		$this->payload = $payload;
	}

	public function getPusher()
	{
		return $payload['pusher']['name'];
	}

	public function getBranch()
	{
		$ref = $payload['ref'];

		return substr($ref, 11); // Take off the "refs/heads/" and you get the branch name
	}
}
