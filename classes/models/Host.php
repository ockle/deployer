<?php

namespace Deployer\Models;

class Host extends \Illuminate\Database\Eloquent\Model
{
	public function projects()
	{
		return $this->hasMany('Deployer\Models\Project');
	}

	public function users()
	{
		return $this->belongsToMany('Deployer\Models\User');
	}
}
