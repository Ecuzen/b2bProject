@if(session('success'))
	<div class="alert alert-success" role="alert">
		{{session('success')}}
	</div>
@endif
@if(session('error'))
	<div class="alert alert-danger" role="alert">
		{{session('error')}}
	</div>
@endif

@if(session('warning'))
	<div class="alert alert-warning" role="alert">
		{{session('warning')}}
	</div>
@endif
@if(session('SUCCESS'))
	<div class="alert alert-success" role="alert">
		{{session('SUCCESS')}}
	</div>
@endif

@if(session('ERROR'))
	<div class="alert alert-warning" role="alert">
		{{session('ERROR')}}
	</div>
@endif

