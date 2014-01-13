<?php

namespace Deployer\Model;

class User extends \Cartalyst\Sentry\Users\Eloquent\User
{
	public static $rules = array(
		'first_name'            => 'required',
		'last_name'             => 'required',
		'email'                 => 'required|email',
		'password'              => 'required_if:type,add|confirmed',
		'password_confirmation' => 'required_with:password'
	);

	public static $messages = array(
		'first_name.required'                 => 'First name is a required field',
		'last_name.required'                  => 'Last name is a required field',
		'email.required'                      => 'Email is a required field',
		'email.email'                         => 'Email must be a valid email address',
		'password.required_if'                => 'Password is a required field',
		'password.confirmed'                  => 'Passwords do not match',
		'password_confirmation.required_with' => 'Please confirm the password'
	);

	public function deployments()
	{
		return $this->hasMany('Deployer\Model\Deployment');
	}

	public function usernames()
	{
		return $this->hasMany('Deployer\Model\Username');
	}
}
