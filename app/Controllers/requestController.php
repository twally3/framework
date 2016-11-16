<?php

use Framework\Core\HTTP\Controller as Controller;
use Framework\Core\HTTP\Route as Route;
use Framework\Core\HTTP\Request as Request;

Class RequestController extends Controller {

	public function get() {
    $this->view('Requests/get', ['post' => $_POST, 'p' => '<h3>HEY</h3>', 'route' => Route::path('PostRequestTest')]);
  }

  public function post(Request $request) {
  	echo $request->path();
		debugArray($request->all());
		echo $request->is('/testing/*');
		echo $request->text;
		echo $request->input('tedxt', 'default');

		echo $request->isMethod('post');

		debugArray($request->only('text', '_token'));

		debugArray($request->exclude('text', 'submit'));

		echo $request->has('_token');

		// $request->flash();
		// $request->flashOnly('text', '_token');
		// $request->flashExcept('submit');

		echo $request->old('submit');
		// $request->emptyFlash();

		debugArray($_SESSION);


    // $this->view('Requests/post', ['post' => $_POST]);
    // $this->json($_POST);
  }
}