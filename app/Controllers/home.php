<?php

use Framework\Core\HTTP\Controller as Controller;
use Framework\Core\HTTP\Request as Request;
use Framework\Core\HTTP\Route as Route;

class Home extends Controller {

  public function profiles($username) {

    $user = User::where('user_name = ', $username)->first();

    $this->view('home/orm', ['user' => $user]);
  }

  public function posts() {
    $posts = Post::all();
    $this->view('home/posts', ['posts' => $posts]);


    // $x = Post::where('post_author = ', '2')->where('post_author =', '5')->operators(['OR'])->orderBy('post_id', 'DESC')->get();
    //
    // $x = Post::where('post_author = ', '5')->delete();
    //
    // $x = Post::find([1,2,5]);
    // $x = Post::all();
    // $x = Post::where('post_author = ', '2')->max('post_date');
    //
    // $post = new Post;
    //
    // $post->test_content = 'This is some more content';
    //
    // $post->save();
    //
    // echo Post::where('id > ', '2')->update(['test_content' => 'this was updated!']);
  }

  public function ORM() {

    $artists = Artist::all();

    // debugArray($data);

    // $artists = Artist::all();

    $this->view('home/orm', ['artists' => $artists]);
    // $this->rawView('home/orm.tea');

  }

  public function index() {
    include '../vendor/max/framework/src/core/views/noRoutes.php';
  }

  public function hello($name='') {
    $this->view("home/hello", ['name' => $name]);
  }

  public function page1($name = '') {
    $arr = [1,2,3,4,5,6];

    $this->view("home/page1", [
      'name' => $name,
      'para' => '<h1>This is test</h1>',
      'path' => Route::path('geoff'),
      'arr' => $arr,
      'obj' => Artist::all()
    ]);

    // $this->rawView('home/page1.tea');
  }

  public function page2() {
    $this->view("home/page2");
  }

  public function anchor() {
    $this->view('home/anchor');
  }

  public function test() {
    $this->test2(function($name) {
      echo "Your name is {$name}";
    });
  }

  public function test2(Closure $callback) {
    $callback('Max');
  }
  public function middleware() {
    $this->view('home/middleware');
  }
}
