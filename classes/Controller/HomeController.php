<?php

namespace Deployer\Controller;

use Deployer\Application;

class HomeController
{
    public function actionView(Application $app)
    {
        $data = array(
        );

        return $app['blade']->make('home', $data);
    }
}
