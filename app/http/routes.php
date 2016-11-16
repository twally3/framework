<?php

use Framework\Core\HTTP\Request as Request;
use Framework\Core\HTTP\Route as Route;

Route::get('/', 'home@index');
Route::get('/home/{name}', 'home@hello');

Route::get('/function/{name}?/{age}', function($name, $age = null) {
  echo "name: $name, age: $age";
});

Route::get('/closure', 'home@test');

Route::group(['prefix' => '/testing', 'middleware' => 'http'], function() {
	
	Route::get('/requests', 'requestController@get');
	Route::post('/requests', 'requestController@post')->name('RequestPost');
	Route::put('/requests', 'home@post');
	Route::delete('/requests', 'home@post');
	
});

Route::get('/ormjson', 'ormController@index');


Route::get('/page1/{name}?', 'home@page1');
Route::get('/page2', 'home@page2');
Route::get('/anchor', 'home@anchor')->name('anchor');
Route::get('/orm', 'home@orm');
Route::get('/posts', 'home@posts');
Route::get('/profiles/{username}', 'home@profiles');

Route::group(['prefix' => '/files'], function() {

	Route::get('/upload', 'fileController@index');
	Route::post('/One', 'fileController@recieveOne')->name('PostOneFile');
	Route::post('/Many', 'fileController@recieveMany')->name('PostManyFiles');

});

Route::group(['prefix' => '/admin'], function() {

  Route::get('/home', 'home@index');
  Route::get('/bant', function() {
    echo 'JELLO WORLD';
  });

});