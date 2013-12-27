@extends('layout')

@section('title')
Home
@stop

@section('content')
	<h2>Activity Feed</h2>

	<ul class="feed no-bullet">
		<li class="row">
			<div class="small-2 medium-1 columns">
				<i class="fa fa-cogs round"></i>
			</div>
			<div class="small-7 medium-9 columns">
				<a href="">User</a> automatically deployed <a href="">Example.com</a>
			</div>
			<div class="small-3 medium-2 columns"><span class="has-tip" title="01/01/2013 11:11:12" data-tooltip>1 hour ago</span></div>
		</li>

		<li class="row">
			<div class="small-2 medium-1 columns">
				<i class="fa fa-wrench round"></i>
			</div>
			<div class="small-7 medium-9 columns">
				<a href="">User</a> manually deployed <a href="">Example.com</a>
			</div>
			<div class="small-3 medium-2 columns"><span class="has-tip" title="01/01/2013 11:11:12" data-tooltip>1 hour ago</span></div>
		</li>
	</ul>
@stop