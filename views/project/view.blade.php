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
		<dt>Deployment type</dt>
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
		<button class="large button"><i class="fa fa-cog"></i> Settings</button>
	</li>
</ul>

<h3>Deployment history</h3>

<table class="column">
	<thead>
		<tr class="row">
			<th class="small-3 medium-3 columns">Date</th>
			<th class="small-6 medium-7 columns">Commit</th>
			<th class="small-3 medium-2 columns">User</th>
		</tr>
	</thead>
	<tbody>
		<tr class="row">
			<td class="small-3 medium-3 columns">11/11/2011 11:11:11</td>
			<td class="small-6 medium-7 columns">This is the commit message</td>
			<td class="small-3 medium-2 columns">
				<a href="">User</a>
			</td>
		</tr>
	</tbody>
</table>
@stop