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
		return $this->hasMany('Deployer\Models\Project');
	}

	public function users()
	{
		return $this->belongsToMany('Deployer\Models\User');
	}

	public function scopeNameAsc($query)
	{
		return $query->orderBy('name', 'asc');
	}
}
