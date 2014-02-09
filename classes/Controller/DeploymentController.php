<?php

namespace Deployer\Controller;

use Deployer\Application;
use Deployer\Model\Project;
use Deployer\Model\Username;
use Deployer\Model\Deployment;
use Symfony\Component\Process\Process;

class DeploymentController
{
    public function actionHook($hash, Application $app)
    {
        // Find project from hash
        $project = Project::where('hash', '=', $hash)->first();

        // Check that project exists and is set to be automatically triggered to deploy
        if ((is_null($project)) || ($project->trigger != 'automatic')) {
            $app->abort(404);
        }

        // Create host object
        $projectHost = $project->host;

        $host = new $projectHost($app['request']);

        // Check pushed branch is the branch that the project deploys from
        $branch = $host->getBranch();

        if ($project->branch != $branch) {
            $app->abort(404);
        }

        // Check the pusher is a user
        $pusher = $host->getPusher();

        $username = Username::where('host', '=', $projectHost)->where('username', '=', $host->getPusher())->first();

        if (is_null($username)) {
            $app->abort(404);
        }

        $user = $username->user;

        // Create a record of the deployment
        $deployment = new Deployment;
        $deployment->project()->associate($project);
        $deployment->user()->associate($user);

        // Start deployment
        chdir($project->directory);

        $fetch = new Process('git fetch origin ' . $project->branch);
        $fetch->run();

        if (!$fetch->isSuccessful()) {
            $deployment->error('Error fetching remote');

            $app->abort(500);
        }

        $reset = new Process('git reset --hard FETCH_HEAD');
        $reset->run();

        if (!$reset->isSuccessful()) {
            $deployment->error('Error resetting project to fetched files');

            $app->abort(500);
        }

        $clean = new Process('git clean -df');
        $clean->run();

        if (!$clean->isSuccessful()) {
            $deployment->error('Error cleaning project');

            $app->abort(500);
        }

        // Deployment successful
        $deployment->message = $host->getLastCommitMessage();
        $deployment->status = 1;

        $deployment->save();

        return true;
    }
}
