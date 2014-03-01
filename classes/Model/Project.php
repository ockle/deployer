<?php

namespace Deployer\Model;

class Project extends \Illuminate\Database\Eloquent\Model
{
    public static $rules = array(
        'name'       => 'required',
        'directory'  => 'required|directory|git',
        'host'       => 'required|host',
        'repository' => 'required',
        'branch'     => 'required',
        'trigger'    => 'required|in:manual,automatic',
        'hash'       => 'required|alpha_num|size:32'
    );

    public static $messages = array(
        'name.required'         => 'Name is a required field',
        'directory.required'    => 'Directory is a required field',
        'directory.directory'   => 'Directory is not a valid directory',
        'directory.git'         => 'Directory does not contain a git repository',
        'host.required'         => 'Please choose a host',
        'host.host'             => 'Host is not a valid host',
        'repository.required'   => 'Repository is a required field',
        'branch.required'       => 'Branch is a required field',
        'trigger.required'      => 'Please choose whether deployments should be triggered manually or automatically',
        'trigger.in'            => 'Deployment trigger method is not valid',
        'hash.required'         => 'Deployment hook URL is not valid',
        'hash.alpha_num'        => 'Deployment hook URL is not valid',
        'hash.size'             => 'Deployment hook URL is not valid'
    );

    public function deployments()
    {
        return $this->hasMany('Deployer\Model\Deployment')->mostRecentFirst();
    }

    public function lastSuccessfulDeployment()
    {
        return $this->hasOne('Deployer\Model\Deployment')->successful()->mostRecentFirst()->take(1);
    }

    public function isTriggeredManually()
    {
        return $this->trigger == 'manual';
    }

    public function isTriggeredAutomatically()
    {
        return $this->trigger == 'automatic';
    }

    public function triggerManually()
    {
        $this->trigger = 'manual';
    }

    public function triggerAutomatically()
    {
        $this->trigger = 'automatic';
    }
}
