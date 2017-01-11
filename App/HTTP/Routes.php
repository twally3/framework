<?php

// ------------------------------ AUTH ROUTES ------------------------------ //

Route::get('/', 'AuthController@index');

Route::get('/dashboard', 'AuthController@loggedin')->middleware(['auth']);
Route::get('/logout', 'AuthController@doLogout')->name('logout');

Route::group(['middleware' => 'authRedirect'], function() {
	Route::get('/login', 'AuthController@showLogin');
	Route::get('/register', 'AuthController@showRegister');
});

Route::group(['middleware' => 'web'], function() {
	Route::post('/login', 'AuthController@doLogin')->name('login');
	Route::post('/register', 'AuthController@doRegister')->name('register');
});

// ---------------------------- END AUTH ROUTES ---------------------------- //
// 
Route::get('/test/{thing}/{2}/{3}', function(Framework\Core\HTTP\Request $r, Framework\Core\Database\Table $t, $thing, Framework\Core\HTTP\Validator $v, $two, $three) {
	echo 'YES';
});