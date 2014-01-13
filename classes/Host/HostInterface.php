<?php

namespace Deployer\Host;

interface HostInterface
{
	public function __construct(array $payload);

	public function getPusher();

	public function getBranch();
}
