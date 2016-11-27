<?php

use Framework\Core\HTTP\Controller;
use Framework\Core\HTTP\Request;

class AuthController extends Controller {

  public function index() {
    
  }

  public function dashboard() {
  	View::make('auth/dash');
  }

  public function showLogin() {
    $errors = Session::single('errors');
  	View::make('auth/login', ['oldEmail' => Request::old('email'), 'errors' => $errors]);
  }

	public function doLogin(Request $request) {

  	$rules = [
  		'email' => 'required|max:128|email',
  		'password' => 'required|max:128|min:8'
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
  			Route::redirect('/dashboard');
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

	public function showRegister(Request $request) {
  	View::make('auth/register', ['errors' => Session::single('errors')]);
  }

  public function doRegister(Request $request) {

		$rules = [
			'uname' => 'required|max:40|unique:users,username',
			'fname' => 'required|max:60',
			'lname' => 'required|max:60',
			'email' => 'required|max:128|email|matches:cemail|unique:users,email',
			'cemail' => 'required|max:128|email',
			'password' => 'required|max:128|min:8|matches:cpassword',
			'cpassword' => 'required|max:128|min:8'
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
		$user->username = $request->uname;
		$user->email = $request->email;
		$user->password = Auth::hash($request->password);

		$user->save();
  }

}
