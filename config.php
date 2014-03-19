<?php

return array(
	'timezone' => 'Europe/London',
    'database' => array(
        'database' => 'deployer',
        'username' => 'root',
        'password' => ''
    ),
    'hosts' => array(
        'Bitbucket' => 'Deployer\Host\Bitbucket',
        'GitHub'    => 'Deployer\Host\GitHub'
    )
);
