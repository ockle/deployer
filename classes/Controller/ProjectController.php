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
            'hash' => md5(microtime())
        );

        return $app['blade']->make('project.add', $data);
    }
}
