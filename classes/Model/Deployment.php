<?php

namespace Deployer\Model;

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
}
