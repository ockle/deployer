<?php

namespace Deployer\Models;

class Deployment extends \Illuminate\Database\Eloquent\Model
{
	public function project()
	{
		return $this->belongsTo('Deployer\Models\Project');
	}

	public function user()
	{
		return $this->belongsTo('Deployer\Models\User');
	}
}
