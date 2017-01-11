@extends('auth.template')

@section('content')
	<div class="container-fluid">
		@if (Auth::id() !== null)
			<h3>You are logged in!</h3>
		@else
			<h3>Welcome Home</h3>
		@endif


	</div>
@endsection
