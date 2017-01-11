@extends('auth.template')

@section('content')
  <div class="container-fluid">
    @if (!empty($errors))
      <div class="alert alert-danger" role="alert">{$errors[0]}</div>
    @endif


    <h3>Register</h3>
    <form action="{Route::path('register')}" method="post">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" name="name">
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" name="email">
      </div>
      <div class="form-group">
        <label for="email2">Confirm Email:</label>
        <input type="email" class="form-control" name="email2">
      </div>
      <div class="form-group">
        <label for="pass">Password:</label>
        <input type="password" class="form-control" name="password">
      </div>
      <div class="form-group">
        <label for="pass2">Confirm Password:</label>
        <input type="password" class="form-control" name="password2">
      </div>
      {!csrf_field()}
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
@endsection
