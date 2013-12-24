<?php

namespace Deployer\Models;

class Project extends \Illuminate\Database\Eloquent\Model
{
	public function deployments()
	{
		return $this->hasMany('Deployer\Models\Deployment');
	}

	public function host()
	{
		return $this->belongsTo('Deployer\Models\Host');
	}
}
