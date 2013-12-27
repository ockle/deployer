@extends('layout')

@section('title')
Add a project
@stop

@section('content')
	<h2>Add a project</h2>

	<form>
		<label>Name</label>
		<input type="text">

		<label>Host</label>
		<select class="">
			<option>Host 1</option>
			<option>Host 2</option>
		</select>

		<button type="submit" class="button tiny radius"><i class="fa fa-check"></i>Submit</button>

		<a type="submit" class="button secondary tiny radius"><i class="fa fa-times"></i>Cancel</a>
	</form>
@stop