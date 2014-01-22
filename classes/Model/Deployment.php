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

	public function error($message)
	{
		$this->message = $message;
		$this->status = 0;

		return $this->save();
	}
}
