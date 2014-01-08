@extends('layout')

@section('title')
{{ ucfirst($type) }} a host
@stop

@section('content')
<h2>{{ ucfirst($type) }} a host</h2>

@include('partial.success-error')

<form action="" method="post">
	<label>Name</label>
	<input type="text" name="name" value="{{{ $app->oldValue('name') ?: (isset($host->name) ? $host->name : '') }}}">

	<label>Pusher field</label>
	<input type="text" name="pusher_field" value="{{{ $app->oldValue('pusher_field') ?: (isset($host->pusher_field) ? $host->pusher_field : '') }}}">

	<label>Branch field type</label>
	<input type="radio" name="branch_field_type" value="{{ Deployer\Model\Host::BRANCH_FIELD }}" id="branch_field_type_{{ Deployer\Model\Host::BRANCH_FIELD }}" {{ $app->oldValue('branch_field_type') == Deployer\Model\Host::BRANCH_FIELD ? 'checked' : (isset($host->branch_field_type) && ($host->branch_field_type == Deployer\Model\Host::BRANCH_FIELD) ? 'checked' : '') }}>
	<label for="branch_field_type_{{ Deployer\Model\Host::BRANCH_FIELD }}">Branch name</label>
	<input type="radio" name="branch_field_type" value="{{ Deployer\Model\Host::REF_FIELD }}" id="branch_field_type_{{ Deployer\Model\Host::REF_FIELD }}" {{ $app->oldValue('branch_field_type') == Deployer\Model\Host::REF_FIELD ? 'checked' : (isset($host->branch_field_type) && ($host->branch_field_type == Deployer\Model\Host::REF_FIELD) ? 'checked' : '') }}>
	<label for="branch_field_type_{{ Deployer\Model\Host::REF_FIELD }}">Ref name</label>

	<label>Branch field</label>
	<input type="text" name="branch_field" value="{{{ $app->oldValue('branch_field') ?: (isset($host->branch_field) ? $host->branch_field : '') }}}">

	<input type="hidden" name="type" value="{{ $type }}">

	<button type="submit" class="button tiny radius"><i class="fa fa-check"></i>Submit</button>

	<a href="{{ $app->path('host.list') }}" class="button secondary tiny radius"><i class="fa fa-times"></i>Cancel</a>
</form>
@stop