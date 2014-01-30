@if (isset($errorMessages) && !empty($errorMessages))
<div class="error panel radius">
	<p>The following errors were encountered:</p>

	<ul>
		@foreach ($errorMessages as $message)
		<li>{{ $message }}</li>
		@endforeach
	</ul>
</div>
@endif

@if (isset($errorMessage))
<div class="error panel radius">
	<p>{{ $errorMessage }}</p>
</div>
@endif

@if (isset($successMessage))
<div class="success panel radius">
	<p>{{ $successMessage }}</p>
</div>
@endif