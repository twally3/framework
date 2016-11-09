<?php


Route::get('/', 'home@index');
Route::get('/home/{name}', 'home@hello');
Route::get('/sessions', 'home@sessions');

Route::get('/test/index', 'test@index');

Route::get('/csrf', 'home@csrf');

Route::get('/function/{name}?/{age}', function($name, $age = null) {
  echo "name: $name, age: $age";
});

Route::get('/testing/requests', 'home@get');
Route::post('/requests', function(Request $request) {
	echo $request->path();
	debugArray($request->all());
	echo $request->is('/testing/*');
	echo $request->text;
	echo $request->input('tedxt', 'default');

	echo $request->isMethod('post');

	debugArray($request->only('text', '_token'));

	debugArray($request->exclude('text', 'submit'));

	echo $request->has('_token');
})->name('geoff');


Route::put('/requests', 'home@post');
Route::delete('/requests', 'home@post');

Route::get('/page1/{name}?', 'home@page1');
Route::get('/page2', 'home@page2');

Route::get('/anchor', 'home@anchor')->name('anchor');

Route::get('/fac', 'home@fac');

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