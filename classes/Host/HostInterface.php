<?php

namespace Deployer\Host;

use Symfony\Component\HttpFoundation\Request;

interface HostInterface
{
	public function __construct(Request $request);

	public function domainName();

	public function getPusher();

	public function getBranch();

	public function getLastCommitMessage();
}
