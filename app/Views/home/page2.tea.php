@extends('main')

@section('title')
  <title>Page 2</title>
@endsection

@section('content')
  <section>
    Contact us page <br>
    You're really gonna love it here! <br>
    <a href="{{=Route::path('anchor')}}#aboutSection">This is a link</a>

  </section>
@endsection

@section('head')
  The best website in the whole world!
@endsection
