<?php

namespace Deployer\Controller;

use Deployer\Application;
use Deployer\Model\Deployment;

class HomeController
{
    public function actionView(Application $app)
    {
        $deployments = Deployment::with(array('project', 'user'))->mostRecentFirst()->take(10)->get();

        $data = array(
            'deployments' => $deployments
        );

        return $app['blade']->make('home', $data);
    }
}
