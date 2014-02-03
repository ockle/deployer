@if (isset($errorMessages) && !empty($errorMessages))
<div class="alert-box alert radius" data-alert>
	The following errors were encountered:

	<ul>
		@foreach ($errorMessages as $message)
		<li>{{ $message }}</li>
		@endforeach
	</ul>
</div>
@endif

@if (isset($errorMessage))
<div class="alert-box alert radius" data-alert>
	{{ $errorMessage }}
	<a href="#" class="close">&times;</a>
</div>
@endif

@if (isset($successMessage))
<div class="alert-box success radius" data-alert>
	{{ $successMessage }}
	<a href="#" class="close">&times;</a>
</div>
@endif