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
        ) + $app->getRedirectData();

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
        ) + $app->getRedirectData();

        return $app['blade']->make('project.add-edit', $data);
    }

    public function actionProcessAdd(Application $app)
    {
        $input = $app['request']->request->all();

        $validation = $app['validator']($input, Project::$rules, Project::$messages);

        if ($validation->passes()) {
            $project = new Project;

            $project->name = $input['name'];
            $project->directory = $input['directory'];
            $project->repository = $input['repository'];
            $project->branch = $input['branch'];
            $project->trigger = $input['trigger'];
            $project->hash = $input['hash'];
            $project->host = $validation->information['repositoryHostName'];

            $project->save();

            return $app->redirect('project.list', array(
                'successMessage' => 'Project successfully added'
            ));
        }

        return $app->forward('project.add', array(
            'errorMessages' => $validation->messages()->all(),
            'oldInput'      => $app['request']->request->all()
        ));
    }
}
