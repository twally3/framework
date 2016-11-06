@extends('main')

@section('title')
  <title>Page 1</title>
@endsection

@section('content')
    {$path}
    Welcome to the website {$name} <br>
    {$para}
    You're really gonna love it here!
    {time()} <br>
@endsection

@section('head')
  Welcome {$name}
@endsection

@section('loop')
  @foreach ($arr as $key => $name)
    <p>
      {$name}
    </p>
  @endforeach
@endsection
