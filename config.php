<?php

return array(
    'database' => array(
        'database' => 'deployer',
        'username' => 'root',
        'password' => ''
    ),
    'hosts' => array(
        'Bitbucket' => 'Deployer\Model\Host\Bitbucket',
        'GitHub'    => 'Deployer\Model\Host\GitHub'
    )
);
