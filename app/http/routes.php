<?php

Route::get('/', function() {
	View::make('home/anchor');
});

// Route::get('/', 'HomeController@index');
Route::get('/home/{name}', 'HomeController@hello');
Route::get('/anchor', 'HomeController@anchor')->name('anchor');


Route::post('/test', function() {
	echo 'TESTING';
})->name('middleware')->middleware(['web']);

Route::get('/form', function() {
	$route = Route::path('middleware');
	return View::make('home/form', ['route' => $route]);
});


Route::get('/test', 'HomeController@test');


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


Route::get('/dashboard', 'AuthController@dashboard')->middleware(['auth']);

Route::get('/login', 'AuthController@showLogin');
Route::get('/register', 'AuthController@showRegister');

Route::get('/logout', 'AuthController@doLogout');

Route::group(['middleware' => 'web'], function() {
	Route::post('/login', 'AuthController@doLogin')->name('login');
	Route::post('/register', 'AuthController@doRegister')->name('register');
});

Route::get('/user', function() {
	debugArray(Auth::user());
});