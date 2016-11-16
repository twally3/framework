<?php namespace Framework\Core\HTTP;

class Route {

  protected static $_prefix = null;
  protected static $_middleware = null;

  protected static $_uri = [];
  protected static $_requestTypes = ['put', 'post', 'delete'];

  protected static $_controller;
  protected static $_method;
  protected static $_params = [];

  public static function name($name) {
    if (!array_key_exists($name, self::$_uri)) {
      $uri = self::$_uri[count(self::$_uri) - 1];
      unset(self::$_uri[count(self::$_uri) - 1]);
      self::$_uri[$name] = $uri;
    }
    return new static;
  }

  public static function middleware() {
    // TODO at some point!
  }

  public static function get($uri, $method = null) {
    if ($method != null) {
      self::$_uri[] = self::buildURI($uri, $method, 'get');
      return new static;
    }
  }

  public static function post($uri, $method = null) {
    if ($method != null) {
      self::$_uri[] = self::buildURI($uri, $method, 'post');
      return new static;
    }
  }

  public static function put($uri, $method = null) {
    if ($method != null) {
      self::$_uri[] = self::buildURI($uri, $method, 'put');
      return new static;
    }
  }

  public static function delete($uri, $method = null) {
    if ($method != null) {
      self::$_uri[] = self::buildURI($uri, $method, 'delete');
      return new static;
    }
  }

  public static function buildURI($uri, $method, $rtype) {
    $uri = is_null(self::$_prefix) ? $uri : self::$_prefix . $uri;

    return ['original' => $uri,
            'uri' => self::reg_replace($uri),
            'method' => $method,
            'rtype' => $rtype,
            'middleware' => self::$_middleware];
  }

  public static function reg_replace($uri) {
    return "#^" . preg_replace("#\/\??\{[A-Za-z0-9\_\-]+\}#", "(?:\/([A-Za-z0-9\-\_]+))", "/" . trim($uri, "/")) . "$#";
  }

  public static function group($array, $callback) {
    if (isset($array['prefix'])) {
      self::$_prefix = $array['prefix'];
    }
    if (isset($array['middleware'])) {
      self::$_middleware = is_array($array['middleware']) ? $array['middleware'] : [$array['middleware']];
    }

    call_user_func($callback);
    self::$_prefix = null;
    self::$_middleware = null;
  }

  public static function findMatch() {
    $uriGetParam = isset($_GET['uri']) ? '/' . $_GET['uri'] : '/';
    $rtype = strtolower($_SERVER['REQUEST_METHOD']);

    if ($rtype == 'post') {
      $rtype = isset($_POST['_method']) ? $_POST['_method'] : 'post';
    }

    $key = array_search("uri", array_keys($_GET));
    array_splice($_GET, $key, 1);

    foreach (self::$_uri as $key => $value) {
      if (preg_match($value['uri'], $uriGetParam, self::$_params) && $value['rtype'] == $rtype) {
        array_splice(self::$_params, 0, 1);
        if (!empty($_GET) || !empty($_POST)) {
          array_unshift(self::$_params, new Request);
        }

        return $value;
      }
    }
    return null;
  }

  public static function getMiddleware($request) {
    $middleware = $request['middleware'];
    $x = \Kernal::getProp('routeMiddleware');

    foreach ($middleware as $key => $value) {
      if (array_key_exists($value, $x)) {
        echo "Middleware {$value} Found!";
        require_once "../{$x[$value]}";
        $x = new $value;
        $x->handle();
      }
    }

    die;
    // foreach ($request['middleware'] as $key => $value) {
    //   echo $value . '<br>';
    //   if(file_exists('../app/http/Middleware/' . $value . '.php')) {
    //     require_once "../app/http/Middleware/" . $value . ".php";
    //     $x = new $value;
    //     $x->handle();
    //   } else {
    //     throw new \Exception("Invalid middleware $value");
    //   }
    // }
  }

  public static function submit() {
    if (!self::$_uri) {
      self::noRoutes();
    }

    $request = self::findMatch();

    if (isset($request['middleware'])) {
      self::getMiddleware($request);
    }

    $callback = is_null($request) ? null : $request['method'];

    if (is_string($callback)) {
      $callback = explode('@', $callback);

      if(file_exists('../app/Controllers/' . $callback[0] . '.php')) {
        self::$_controller = $callback[0];
      } else {
        throw new \Exception('Page does not exist');
      }

      require_once "../app/Controllers/" . self::$_controller . ".php";
      self::$_controller = new self::$_controller;

      if(isset($callback[1]) && method_exists(self::$_controller, $callback[1])) {
        self::$_method = $callback[1];
      } else {
        throw new \Exception('Page does not exist');
      }

      call_user_func_array([self::$_controller, self::$_method], self::$_params);
    } else if (is_callable($callback)) {
      self::$_method = $callback;
      call_user_func_array(self::$_method, self::$_params);
    } else {
      throw new \Exception('Page does not exist');
    }
  }

  static function noRoutes() {
    include '../vendor/max/Framework/src/core/views/noRoutes.php';
    die;
  }

  public static function path($name, $params = []) {
    if (array_key_exists($name, self::$_uri)) {
      $uriObj = self::$_uri;
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

  public static function redirect($redirect) {
    header("Location: {$redirect}");
    die;
  }
}
