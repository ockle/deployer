<?php

namespace Deployer\Model;

class Project extends \Illuminate\Database\Eloquent\Model
{
	public function deployments()
	{
		return $this->hasMany('Deployer\Model\Deployment');
	}
}
