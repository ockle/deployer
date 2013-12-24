<?php

namespace Deployer\Models;

class User extends \Cartalyst\Sentry\Users\Eloquent\User
{
	public function deployments()
	{
		return $this->hasMany('Deployer\Models\Deployment');
	}

	public function hosts()
	{
		return $this->belongsToMany('Deployer\Models\Host');
	}
}
