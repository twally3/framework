<?php

use Framework\Core\HTTP\Controller;
use Framework\Core\HTTP\Request;

class AuthController extends Controller {

  public function index() {
    View::make('auth/index');
  }

  public function showLogin() {
    View::make('auth/login', ['errors' => Session::single('errors')]);
  }

  public function showRegister() {
    View::make('auth/register', ['errors' => Session::single('errors')]);
  }

  public function loggedin() {
  	View::make('auth/dash', ['username' => Auth::user()->name]);
  }

	public function doLogin(Request $request) {

  	$rules = [
  		'email' => 'required|max:128|email',
  		'password' => 'required|max:128'
  	];

  	$validator = Validate::check($request, $rules);
    Session::set('errors', Validate::failList());

  	$request->flashOnly('email');

  	if ($validator) {
  		$userdata = [
  			'email' => $request->email,
  			'password' => $request->password,
  			'remember' => $request->remember
  		];

  		if (Auth::attempt($userdata)) {
  			Route::redirect('/');
  		} else {
        Session::set('errors', ['Email or password is incorrect']);
  			Route::redirect('/login');
  		}

  	} else {
  		redirect('/login');
  	}
  }

  public function doLogout() {
  	if (Auth::logout()) {
  		Route::redirect('/');
  	} else {
  		throw new \Exception("An Error occured!");
  	}
  }

  public function doRegister(Request $request) {

		$rules = [
			'name' => 'required|max:127',
			'email' => 'required|max:128|email|matches:email2|unique:users,email',
			'email2' => 'required|max:128|email',
			'password' => 'required|max:128|min:8|matches:password2',
			'password2' => 'required|max:128|min:8'
		];

		if (Validate::check($request, $rules)) {
			$this->addToUser($request);
			redirect('/login');
		} else {
      Session::set('errors', Validate::failList());
			redirect('/register');
		}
  }

  protected function addToUser($request) {
  	$user = new User;

		$user->name = $request->fname . ' ' . $request->lname;
		$user->email = $request->email;
		$user->password = Auth::hash($request->password);

		$user->save();
  }

}
