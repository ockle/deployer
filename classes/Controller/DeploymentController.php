<?php

namespace Deployer\Controller;

use Deployer\Application;
use Deployer\Model\Project;
use Deployer\Model\User;
use Deployer\Model\Username;
use Deployer\Model\Deployment;
use Symfony\Component\HttpFoundation\Response;

class DeploymentController
{
    public function actionView(Deployment $deployment, Application $app)
    {
        $data = array(
            'deployment' => $deployment
        ) + $app->getRedirectData();

        return $app['blade']->make('deployment.view', $data);
    }

    public function actionManual(Project $project, Application $app)
    {
        $data = array(
            'project' => $project
        );

        return $app['blade']->make('deployment.manual', $data);
    }

    public function actionProcessManual(Project $project, Application $app)
    {
        $user = $app['sentry']->getUser();

        $deployment = new Deployment;
        $deployment->isBeingTriggeredManually();

        $deploymentStatus = $deployment->process($project, $user, $app);

        if ($deploymentStatus) {
            $alert = array(
                'successMessage' => 'Deployment successful'
            );
        } else {
            $alert = array(
                'errorMessage' => 'Deployment failed'
            );
        }

        return $app->redirect(array('deployment.view', array('deployment' => $deployment->id)), $alert);
    }

    public function actionAutomatic($hash, Application $app)
    {
        // Find project from hash
        $project = Project::where('hash', '=', $hash)->first();

        // Check that project exists 
        if (is_null($project)) {
            $app->abort(404, 'Project not found');
        }

        // Check that project is set to be automatically triggered to deploy
        if (!$project->isTriggeredAutomatically()) {
            $app->abort(404, 'Project not set to allow automatic deployment');
        }

        // Create host object
        $projectHostClass = 'Deployer\Host\\' . $project->host;

        $host = new $projectHostClass($app['request']);

        // Check pushed branch is the branch that the project deploys from
        $branch = $host->getBranch();

        if ($project->branch != $branch) {
            $app->abort(404, 'Pushed branch is not set to trigger deployment');
        }

        // Check the pusher is a user
        $pusher = $host->getPusher();

        $username = Username::where('host', '=', $project->host)->where('username', '=', $pusher)->first();

        if (is_null($username)) {
            $app->abort(404, 'Unknown pusher');
        }

        $user = $username->user;

        // OK, all looks good, let's attempt a deployment
        $deployment = new Deployment;
        $deployment->isBeingTriggeredAutomatically();

        if (!$deployment->process($project, $user, $app)) {
            $app->abort(500, 'Deployment failed');
        }

        return 'Deployment successful';
    }
}
