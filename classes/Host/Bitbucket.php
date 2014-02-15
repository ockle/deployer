<?php

namespace Deployer\Host;

use Symfony\Component\HttpFoundation\Request;

class Bitbucket implements HostInterface
{
    public $payload;

    public function __construct(Request $request)
    {
        $this->payload = json_decode($request->request->get('payload'), true);
    }

    public static function domainName()
    {
        return 'bitbucket.org';
    }

    public function getPusher()
    {
        return $this->payload['user'];
    }

    public function getBranch()
    {
        $lastCommit = end($this->payload['commits']);

        return $lastCommit['branch'];
    }
}
