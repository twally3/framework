<?php 
namespace Framework\Core\HTTP;

use Framework\Core\Foundation\Application;
use Framework\Core\Render\Tea;
use Framework\Core\HTTP\Request;

// use Framework\Core\Views\Tea as Tea;

class Controller {

  protected $app;

  // public function model($model) {
  //   require_once '../app/models/' . $model . '.php';
  //   return new $model();
  // }

  public function __construct(Application $app, Tea $view, Validator $validator) {
    $this->app = $app;
    $this->view = $view;
    $this->validator = $validator;
  }

  public function rawView($view, $data = []) {
    require_once $this->app->basepath . '/app/views/' . $view . '.php';
  }

  public function json($array) {
    echo json_encode($array);
  }

  public function validate(Request $request, array $array) {
    return $this->validator->check($request, $array);
  }
}
