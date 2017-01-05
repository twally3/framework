<?php

namespace Framework\Core\HTTP;

use Framework\Core\Foundation\Application;

use ReflectionFunction;
use ReflectionMethod;

class Router {

  /**
   * the application container
   * @var Framework\Core\Foundation\Application
   */
  protected $app;

  /**
   * The current GROUP prefix
   * @var string
   */
	protected $_prefix = null;

  /** 
   * The current group middleware
   * @var array
   */
  protected $_middleware = null;


  /**
   * The array of all defined URI's
   * @var array
   */
	protected $_uri = [];


  /**
   * The target controller
   * @var mixed
   */
  protected $_controller;

  /**
   * The target method
   * @var mixed
   */
  protected $_method;

  /**
   * The method params
   * @var array
   */
  protected $_params = [];


  /**
   * Bind dependencies to the class
   * @param Application $app The application container
   */
	public function __construct(Application $app) {
    $this->app = $app;
	}


  /**
   * Give a route a name
   * @param  string $name The unique reference name of the route
   * @return $this
   */
  public function name($name) {
    if (!array_key_exists($name, $this->_uri)) {
      $uri = end($this->_uri);
      unset($this->_uri[key($this->_uri)]);
      $this->_uri[$name] = $uri;
    }
    return $this;
  }


  /**
   * Add middleware to route
   * @param  Array  $array The array of middleware names
   * @return $this
   */
  public function middleware(Array $array) {
    end($this->_uri);
    $x = $this->_uri[key($this->_uri)]['middleware'] ?: [];
    $this->_uri[key($this->_uri)]['middleware'] = array_merge($x, $array);

    return $this;
  }


  /**
   * Set a GET route
   * @param  string $uri    The URI
   * @param  mixed  $method Closure or controller reference
   * @return $this
   */
	public function get($uri, $method = null) {
    if ($method != null) {
      $this->_uri[] = $this->buildURI($uri, $method, 'get');
      return $this;
    }
	}


  /**
   * Set a POST route
   * @param  string $uri    The URI
   * @param  mixed  $method Closure or controller reference
   * @return $this
   */
  public function post($uri, $method = null) {
    if ($method != null) {
      $this->_uri[] = $this->buildURI($uri, $method, 'post');
      return $this;
    }
  }


  /**
   * Create a PUT Route
   * @param  string $uri    The URI
   * @param  mixed  $method Closure or controller reference
   * @return $this
   */
  public function put($uri, $method = null) {
    if ($method != null) {
      $this->_uri[] = $this->buildURI($uri, $method, 'put');
      return $this;
    }
  }


  /**
   * Creates a DELETE route
   * @param  string $uri    The URI
   * @param  mixed  $method Closure or controller reference
   * @return $this
   */
  public function delete($uri, $method = null) {
    if ($method != null) {
      $this->_uri[] = $this->buildURI($uri, $method, 'delete');
      return $this;
    }
  }


  /**
   * Builds the URI array
   * @param  string $uri    The URI
   * @param  mixed  $method Closure or controller reference
   * @param  string $rtype  POST, GET, DELETE, PUT
   * @return array          The URI array element
   */
	public function buildURI($uri, $method, $rtype) {
    $uri = is_null($this->_prefix) ? $uri : $this->_prefix . $uri;

    return ['original' => $uri,
            'uri' => $this->reg_replace($uri),
            'method' => $method,
            'rtype' => $rtype,
            'middleware' => $this->_middleware];
	}


  /**
   * Turn the passed URI into a regex
   * @param  string $uri The URI
   * @return string      The REGEX query
   */
	public function reg_replace($uri) {
    return "#^" . preg_replace("#\/\??\{[A-Za-z0-9\_\-]+\}#", "(?:\/([A-Za-z0-9\-\_]+))", "/" . trim($uri, "/")) . "$#";
  }


  /**
   * Define a groupe of routes with the same properties
   * @param  array   $array    An array of all properties
   * @param  closure $callback The closure to define routes
   * @return void
   */
  public function group($array, $callback) {
    if (isset($array['prefix'])) {
      $this->_prefix = $array['prefix'];
    }
    if (isset($array['middleware'])) {
      $this->_middleware = is_array($array['middleware']) ? $array['middleware'] : [$array['middleware']];
    }

    call_user_func($callback);
    $this->_prefix = null;
    $this->_middleware = null;
  }


  /**
   * Get the URI by named route
   * @param  string $name   Named route
   * @param  array  $params Paramed to fill
   * @return string         The stored URI with params
   */
  public function path($name, $params = []) {
    if (array_key_exists($name, $this->_uri)) {
      $uriObj = $this->_uri;
      $groups = preg_split("/\//", $uriObj[$name]['original'],-1, PREG_SPLIT_DELIM_CAPTURE);
      $count = 0;

      foreach ($groups as $key => $value) {
        if (preg_match("/\??\{[A-Za-z0-9\_\-]+\}\??/", $value)) {
          if (!isset($params[$count])) {
            return null;
          } else if ($params[$count] == '?') {
            unset($groups[$key]);
            $count++;
          } else {
            $groups[$key] = $params[$count];
            $count++;
          }
        }
      }
      return implode('/', $groups);
    }
  }


  /**
   * Redirect to a new location
   * @param  string $redirect The path to route to
   * @return void
   */
  public function redirect($redirect) {
    header("Location: {$redirect}");
    die;
  }


  /**
   * Finds a match in the stored URI based on the CURRENT URI
   * @return mixed null or the matched array element
   */
  public function findMatch() {
    $uriGetParam = isset($_GET['uri']) ? '/' . $_GET['uri'] : '/';
    $rtype = strtolower($_SERVER['REQUEST_METHOD']);

    if ($rtype == 'post') {
      $rtype = isset($_POST['_method']) ? $_POST['_method'] : 'post';
    }

    $key = array_search("uri", array_keys($_GET));
    array_splice($_GET, $key, 1);

    foreach ($this->_uri as $key => $value) {
      if (preg_match($value['uri'], $uriGetParam, $this->_params) && $value['rtype'] == $rtype) {
        array_splice($this->_params, 0, 1);
        return $value;
      }
    }
    return null;
  }


  /**
   * Run the route
   * @return array The method to run and arguements
   */
  public function submit() {

    if (!$this->_uri) {
      $this->noRoutes();
    }

    $request = $this->findMatch();
    $callback = is_null($request) ? null : $request['method'];

    if (is_string($callback)) {
      $callback = explode('@', $callback);

      if(file_exists($this->app->basepath . '/App/Controllers/' . $callback[0] . '.php')) {
        $this->_controller = $callback[0];
      } else {
        throw new \Exception('Page does not exist');
      }

      require_once $this->app->basepath . "/App/Controllers/" . $this->_controller . ".php";

      $this->app->bind(strtolower($this->_controller), $this->_controller);
      $this->_controller = $this->app->resolve(strtolower($this->_controller));

      if(isset($callback[1]) && method_exists($this->_controller, $callback[1])) {
        $this->_method = $callback[1];
      } else {
        throw new \Exception('Page does not exist');
      }

      return [[$this->_controller, $this->_method], $this->_params, $request['middleware']];
    } else if (is_callable($callback)) {
      return [$callback, $this->_params, $request['middleware']];
    } else {
      throw new \Exception('Page does not exist');
      die;
    }
  }


  /**
   * Code to run if no routes are set
   * @return void
   */
  protected function noRoutes() {
    include $this->app->basepath . '/vendor/max/Framework/src/Core/Render/noRoutes.php';
    die;
  }
}