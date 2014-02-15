<?php

namespace Deployer\Model;

use Deployer\Application;
use Symfony\Component\Process\Process;

class Deployment extends \Illuminate\Database\Eloquent\Model
{
    public function project()
    {
        return $this->belongsTo('Deployer\Model\Project');
    }

    public function user()
    {
        return $this->belongsTo('Deployer\Model\User');
    }

    public function scopeMostRecentFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function process(Project $project, User $user, Application $app)
    {
        $this->project()->associate($project);
        $this->user()->associate($user);

        // Start deployment
        if (!@chdir($project->directory)) {
            $this->error('Error changing directory');

            return false;
        }

        $fetch = new Process('git fetch ' . $project->remote . ' ' . $project->branch);
        $fetch->run();

        if (!$fetch->isSuccessful()) {
            $this->error('Error fetching remote: ' . $fetch->getErrorOutput());

            return false;
        }

        $log = new Process('git log --oneline ..' . $project->remote . '/' . $project->branch);
        $log->run();

        if (!$log->isSuccessful()) {
            $this->error('Error getting log of deployed commits: ' . $log->getErrorOutput());

            return false;
        }

        $reset = new Process('git reset --hard FETCH_HEAD');
        $reset->run();

        if (!$reset->isSuccessful()) {
            $this->error('Error resetting project to fetched files: ' . $reset->getErrorOutput());

            return false;
        }

        $clean = new Process('git clean -df');
        $clean->run();

        if (!$clean->isSuccessful()) {
            $this->error('Error cleaning project: ' . $clean->getErrorOutput());

            return false;
        }

        $currentCommit = new Process('git show-branch --sha1-name ' . $project->branch);
        $currentCommit->run();

        if (!$currentCommit->isSuccessful()) {
            $this->error('Error fetching current commit: ' . $currentCommit->getErrorOutput());

            return false;
        }

        // Deployment successful
        return $this->success($currentCommit->getOutput());
    }

    protected function success($message)
    {
        return $this->finish($message, 1);
    }

    protected function error($message)
    {
        return $this->finish($message, 0);
    }

    protected function finish($message, $status)
    {
        $this->message = $message;
        $this->status = (int) (bool) $status;

        return $this->save();
    }
}
