@if (isset($errorMessages) && !empty($errorMessages))
<div class="error panel radius">
	<ul>
		@foreach ($errorMessages as $message)
		<li>{{ $message }}</li>
		@endforeach
	</ul>
</div>
@endif

@if (isset($successMessage))
<div class="success panel radius">
	<p>{{ $successMessage }}</p>
</div>
@endif