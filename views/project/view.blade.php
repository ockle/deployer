@extends('layout')

@section('title')
{{ $project->name }}
@stop

@section('content')
<h2>{{ $project->name }}</h2>

<dl class="panel radius row">
	<div class="small-12 medium-6 columns">
		<dt>Last deployed</dt>
		<dd>1 hour ago by <a href="">User</a></dd>

		<dt>Respository</dt>
		<dd>
			<a href="">View on Bitbucket</a>
		</dd>
	</div>

	<div class="small-12 medium-6 columns">
		<dt>Deployment trigger</dt>
		<dd><i class="fa fa-cogs round"></i> Automatic</dd>

		<dt>Directory</dt>
		<dd>
			/var/www/project.com/
		</dd>
	</div>

	<div class="small-12 columns">
		<dt>Current commit</dt>
		<dd>This is the commit message</dd>
	</div>
</dl>

<ul class="button-group radius">
	<li class="small-12 medium-6">
		<button class="large success button"><i class="fa fa-download"></i> Deploy</button>
	</li>
	<li class="small-12 medium-6">
		<a href="{{ $app->path('project.edit', array('project' => $project->id)) }}" class="large button"><i class="fa fa-cog"></i> Settings</a>
	</li>
</ul>

<h3>Deployment history</h3>

<table class="column">
	<thead>
		<tr class="row">
			<th class="small-1 text-center">Status</th>
			<th class="small-2">Date</th>
			<th class="small-6">Commit</th>
			<th class="small-3">User</th>
		</tr>
	</thead>
	<tbody>
		<tr class="row">
			<td>
				<span class="label success radius column">Success</span>
			</td>
			<td>11/11/2011 11:11:11</td>
			<td>This is the commit message</td>
			<td>
				<a href="">User</a>
			</td>
		</tr>
		<tr class="row">
			<td>
				<span class="label alert radius column">Error</span>
			</td>
			<td>11/11/2011 11:11:11</td>
			<td>This is the commit message</td>
			<td>
				<a href="">User</a>
			</td>
		</tr>
	</tbody>
</table>
@stop