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

    public function scopeSuccessful($query)
    {
        return $query->where('status', '=', 1);
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

        $startTime = microtime(true);

        $fetch = new Process('git fetch ' . $project->remote);
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

        $this->log = $log->getOutput();

        $reset = new Process('git reset --hard ' . $project->remote . '/' . $project->branch);
        $reset->run();

        if (!$reset->isSuccessful()) {
            $this->error('Error resetting project to fetched files: ' . $reset->getErrorOutput());

            return false;
        }

        $this->duration = ceil((microtime(true) - $startTime) * 1000);
        $this->status = 1;

        $currentCommit = new Process('git show-branch --sha1-name ' . $project->branch);
        $currentCommit->run();

        // This is still considered a successful deployment, as the files have been changed
        if (!$currentCommit->isSuccessful()) {
            $this->message = 'Error fetching current commit: ' . $currentCommit->getErrorOutput();
        } else {
            // Deployment successful
            $this->message = $currentCommit->getOutput();
        }

        $this->save();

        return true;
    }

    protected function error($message)
    {
        $this->message = $message;
        $this->status = 0;

        return $this->save();
    }
}
