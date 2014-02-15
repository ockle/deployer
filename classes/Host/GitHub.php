<?php

namespace Deployer\Host;

use Symfony\Component\HttpFoundation\Request;

class GitHub implements HostInterface
{
    public $payload;

    public function __construct(Request $request)
    {
        $this->payload = json_decode($request->request->get('payload'), true);
    }

    public static function domainName()
    {
        return 'github.com';
    }

    public function getPusher()
    {
        return $this->payload['pusher']['name'];
    }

    public function getBranch()
    {
        $ref = $this->payload['ref'];

        return substr($ref, 11); // Take off the "refs/heads/" and you get the branch name
    }
}
