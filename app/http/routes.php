<?php


Route::get('/', 'home@index');
Route::get('/home/{name}', 'home@hello');
Route::get('/sessions', 'home@sessions');

Route::get('/csrf', 'home@csrf');

Route::get('/function/{name}?/{age}', function($name, $age = null) {
  echo "name: $name, age: $age";
});

Route::get('/requests', 'home@get');
Route::post('/requests', 'home@post')->name('geoff');

Route::get('/page1/{name}?', 'home@page1');
Route::get('/page2', 'home@page2');

Route::get('/anchor', 'home@anchor')->name('anchor');

Route::get('/fac', 'home@fac');

Route::get('/orm', 'home@orm');

Route::get('/posts', 'home@posts');

Route::get('/profiles/{username}', 'home@profiles');

Route::group(['prefix' => '/admin'], function() {
  Route::get('/home', 'home@index');

  Route::get('/bant', function() {
    echo 'JELLO WORLD';
  });
});