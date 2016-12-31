<?php 
namespace Framework\Core\HTTP;

use Framework\Core\Foundation\Application;
use Framework\Core\Render\Tea;
use Framework\Core\HTTP\Request;

class Controller {

  /**
   * Stores the application container
   * @var Application
   */
  protected $app;


  /**
   * Binds the dependencies to the class
   * @param Application $app       The singleton instance of the application container
   * @param Tea         $view      An instance of the Tea rendering  engine
   * @param Validator   $validator An instance of the Validator
   */
  public function __construct(Application $app, Tea $view, Validator $validator) {
    $this->app = $app;
    $this->view = $view;
    $this->validator = $validator;
  }


  /**
   * Directly requires a HTML file for rendering
   * @param  string $view The path to the view
   * @param  array  $data data to pass to the view
   */
  public function rawView($view, $data = []) {
    require_once $this->app->basepath . '/App/Views/' . $view . '.php';
  }


  /**
   * echos a JSON output
   * @param  array $array The Array for JSON output
   */
  public function json($array) {
    echo json_encode($array);
  }


  /**
   * The function to interact with the validator instance
   * @param  Request $request The request object to validate
   * @param  array   $rules   The list of rules
   * @return Boolean          The bool state of the validation
   */
  public function validate(Request $request, array $rules) {
    return $this->validator->check($request, $rules);
  }
}
