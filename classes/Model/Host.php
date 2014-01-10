<?php

namespace Deployer\Model;

class Host extends \Illuminate\Database\Eloquent\Model
{
	const BRANCH_FIELD = 1;
	const REF_FIELD = 2;

	public static $rules = array(
		'name'              => 'required',
		'pusher_field'      => 'required',
		'branch_field_type' => 'required',
		'branch_field'      => 'required'
	);

	public static $messages = array(
		'name.required'              => 'Name is a required field',
		'pusher_field.required'      => 'Pusher field is a required field',
		'branch_field_type.required' => 'Branch field type is a required field',
		'branch_field.required'      => 'Branch field is a required field'
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
