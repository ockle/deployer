<?php

namespace Deployer\Host;

class Bitbucket implements HostInterface
{
	public $payload;

	public function __construct(array $payload)
	{
		$this->payload = $payload;
	}

	public function getPusher()
	{
		return $payload['pusher'];
	}

	public function getBranch()
	{
		$lastCommit = end($payload['commits']);

		return $lastCommit['branch'];
	}
}
