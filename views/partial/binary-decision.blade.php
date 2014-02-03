<form action="{{ $action }}" method="post">
	<ul class="button-group radius">
		<li class="small-12 medium-6">
			<button type="submit" class="large success button">Yes</button>
		</li>
		<li class="small-12 medium-6">
			<a href="{{ $app->back() }}" class="large alert button">No</a>
		</li>
	</ul>
</form>