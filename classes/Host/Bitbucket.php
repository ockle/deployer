<?php

namespace Deployer\Host;

use Symfony\Component\HttpFoundation\Request;

class Bitbucket implements HostInterface
{
	public $payload;

	public function __construct(Request $request)
	{
		$this->payload = $request->request->get('payload');
	}

	public function getPusher()
	{
		return $payload['pusher'];
	}

	public function getBranch()
	{
		$lastCommit = $this->getLastCommit();

		return $lastCommit['branch'];
	}

	public function getLastCommitMessage()
	{
		$lastCommit = $this->getLastCommit();

		return $lastCommit['message'];
	}

	protected function getLastCommit()
	{
		return end($payload['commits']);
	}
}
