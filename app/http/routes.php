<?php

Route::get('/', 'HomeController@index');
Route::get('/home/{name}', 'HomeController@hello');
Route::get('/anchor', 'HomeController@anchor')->name('anchor');

Route::group(['middleware' => 'web'], function() {

	Route::post('/test', function() {
		echo 'TESTING';
	})->name('middleware');

});

Route::get('/test', 'HomeController@test');

Route::get('/form', function() {
	$route = Route::path('middleware');
	return View::make('home/form', ['route' => $route]);
});

Route::get('/orm', 'HomeController@orm');

Route::group(['prefix' => '/files'], function() {

	Route::get('/upload', 'FileController@index');
	Route::post('/One', 'FileController@recieveOne')->name('PostOneFile');
	Route::post('/Many', 'FileController@recieveMany')->name('PostManyFiles');

});

Route::get('/requests', 'RequestController@get');

Route::group(['prefix' => '/testing', 'middleware' => 'web'], function() {
	
	Route::post('/requests', 'RequestController@post')->name('RequestPost');
	// Route::put('/requests', 'home@post');
	// Route::delete('/requests', 'home@post');
	
});