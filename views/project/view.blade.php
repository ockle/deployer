@extends('layout')

@section('title')
{{ $project->name }}
@stop

@section('content')
<h2>{{ $project->name }}</h2>

<ul class="small-block-grid-1 medium-block-grid-2">
	<li>
		<dl class="panel radius">
			<dt>Last deployed</dt>
			<dd>1 hour ago by <a href="">User</a></dd>

			<dt>Current commit</dt>
			<dd>This is the commit message</dd>

			<dt>Respository</dt>
			<dd>
				<a href="">View on Bitbucket</a>
			</dd>
		</dl>
	</li>
	<li>
		<div class="panel radius row">
			<button class="success radius button small-12 xlarge-6 columns"><i class="fa fa-wrench"></i> Manually deploy</button>
			<button class="disabled radius button small-12 xlarge-6 columns" disabled><i class="fa fa-cogs"></i> Automatically deploying</button>
			<a class="secondary radius button small-12 xlarge-5 columns"><i class="fa fa-cog"></i> Settings</a>
		</div>
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