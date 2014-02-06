<?php

namespace Deployer\Host;

use Symfony\Component\HttpFoundation\Request;

class GitHub implements HostInterface
{
	public $payload;

	public function __construct(Request $request)
	{
		$this->payload = $request->request->get('payload');
	}

	public function domainName()
	{
		return 'github.com';
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

	public function getLastCommitMessage()
	{
		$lastCommit = end($payload['commits']);

		return $lastCommit['message'];
	}
}
