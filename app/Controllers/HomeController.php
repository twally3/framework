<?php

use Framework\Core\HTTP\Controller;
use Framework\Core\HTTP\Request;

class HomeController extends Controller {

  public function index() {
    include $this->app->basepath . '/vendor/max/framework/src/core/render/noRoutes.php';
  }

  public function hello($name='') {
    View::make("home/hello", ['name' => $name]);
  }

  public function anchor() {
    View::make("home/anchor");
  }

  public function test(Request $request) {
  	$val = Validate::check($request, [
  		'name' => 'required',
  		'age' => 'required|max:5'
  	]);

  	if ($val) {
  		echo 'true';
  	} else {
  		echo 'false';
  	}
  }

  public function ORM() {
    $artists = Artist::all();

    echo $artists;

    // foreach ($artists as $artist) {
    //   echo $artist->name;
    //   foreach ($artist->albums as $album) {
    //     echo '-' . $album->name;
    //   }
    // }
  }
}
