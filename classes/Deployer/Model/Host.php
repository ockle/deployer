<?php

namespace Deployer\Model;

class Host extends \Illuminate\Database\Eloquent\Model
{
	public static $rules = array(
		'name' => 'required'
	);

	public static $messages = array(
		'name.required' => 'Name is a required field'
	);

	public function projects()
	{
		return $this->hasMany('Deployer\Model\Project');
	}

	public function users()
	{
		return $this->belongsToMany('Deployer\Model\User');
	}

	public function scopeNameAsc($query)
	{
		return $query->orderBy('name', 'asc');
	}
}
