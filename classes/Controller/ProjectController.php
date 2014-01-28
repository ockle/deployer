<?php

namespace Deployer\Controller;

use Deployer\Application;
use Deployer\Model\Project;

class ProjectController
{
    public function actionList(Application $app)
    {
        $projects = Project::all();

        $data = array(
            'projects' => $projects
        );

        return $app['blade']->make('project.list', $data);
    }

    public function actionView(Project $project, Application $app)
    {
        $data = array(
            'project' => $project
        );

        return $app['blade']->make('project.view', $data);
    }

    public function actionAdd(Application $app)
    {
        $data = array(
            'hosts' => array_keys($app['config']['hosts'])
        );

        return $app['blade']->make('project.add', $data);
    }
}
