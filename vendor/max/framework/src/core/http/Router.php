<?php

namespace Framework\Core\HTTP;

use Framework\Core\Foundation\Application;

use ReflectionFunction;
use ReflectionMethod;

class Router {

  protected $app;

	protected $_prefix = null;
  protected $_middleware = null;

	protected $_uri = [];

  protected $_controller;
  protected $_method;
  protected $_params = [];

	public function __construct(Application $app) {
    $this->app = $app;
	}

  public function name($name) {
    if (!array_key_exists($name, $this->_uri)) {
      $uri = $this->_uri[count($this->_uri) - 1];
      unset($this->_uri[count($this->_uri) - 1]);
      $this->_uri[$name] = $uri;
    }
    return $this;
  }

	public function get($uri, $method = null) {
    if ($method != null) {
      $this->_uri[] = $this->buildURI($uri, $method, 'get');
      return $this;
    }
	}

  public function post($uri, $method = null) {
    if ($method != null) {
      $this->_uri[] = $this->buildURI($uri, $method, 'post');
      return $this;
    }
  }

  public function put($uri, $method = null) {
    if ($method != null) {
      $this->_uri[] = $this->buildURI($uri, $method, 'put');
      return $this;
    }
  }

  public function delete($uri, $method = null) {
    if ($method != null) {
      $this->_uri[] = $this->buildURI($uri, $method, 'delete');
      return $this;
    }
  }

	public function buildURI($uri, $method, $rtype) {
    $uri = is_null($this->_prefix) ? $uri : $this->_prefix . $uri;

    return ['original' => $uri,
            'uri' => $this->reg_replace($uri),
            'method' => $method,
            'rtype' => $rtype,
            'middleware' => $this->_middleware];
	}

	public function reg_replace($uri) {
    return "#^" . preg_replace("#\/\??\{[A-Za-z0-9\_\-]+\}#", "(?:\/([A-Za-z0-9\-\_]+))", "/" . trim($uri, "/")) . "$#";
  }

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

  public function redirect($redirect) {
    header("Location: {$redirect}");
    die;
  }

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

  public function submit() {

    if (!$this->_uri) {
      $this->noRoutes();
    }

    $request = $this->findMatch();
    $callback = is_null($request) ? null : $request['method'];

    if (is_string($callback)) {
      $callback = explode('@', $callback);

      if(file_exists($this->app->basepath . '/app/Controllers/' . $callback[0] . '.php')) {
        $this->_controller = $callback[0];
      } else {
        throw new \Exception('Page does not exist');
      }

      require_once $this->app->basepath . "/app/Controllers/" . $this->_controller . ".php";

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

  protected function noRoutes() {
    include $this->app->basepath . '/vendor/max/Framework/src/core/render/noRoutes.php';
    die;
  }
}