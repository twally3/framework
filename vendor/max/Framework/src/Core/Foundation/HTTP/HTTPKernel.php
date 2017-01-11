<?php

namespace Framework\Core\Foundation\HTTP;

use Framework\Core\Foundation\Application;
use Framework\Core\HTTP\Request;
use Framework\Core\HTTP\Router;

use ReflectionFunction;
use ReflectionMethod;

Class HTTPKernel {

	/**
	 * The application container
	 * @var Application
	 */
	protected $app;

	/**
	 * The router instance
	 * @var Router
	 */
	protected $router;


	/**
	 * The controller method
	 * @var array
	 */
	protected $method;

	/**
	 * The additional args for the method
	 * @var array
	 */
	protected $args;

	/**
	 * The required middleware
	 * @var null
	 */
	protected $middleware = null;


	/**
	 * The request instance
	 * @var Request
	 */
	protected $request;

	protected $shouldLoadRoutes;


	/**
	 * Bind the dependencies and start the session and load the routes
	 * @param Application $app    The application container
	 * @param Router      $router The router instance
	 */
	public function __construct(Application $app, Router $router, $sessions = null, $routes = true) {
		$this->app = $app;
		$this->router = $router;
		$this->shouldLoadRoutes = $routes;

		if (!is_null($sessions)) {
			$this->sessions = $sessions;
		} else {
			$this->sessions = require_once $this->app->basepath . '/App/Bootstrap/sessions.php';
		}

		$this->startSessions();
		$this->loadRoutes();
	}


	/**
	 * Runs the HTTP Kernal
	 * @param  Request $request Passed the current request object
	 * @return string           Currently only returns the word response
	 */
	public function handle(Request $request) {
		$this->request = $request;
		list($this->method, $this->args, $this->middleware) = $this->router->submit();

		if ($this->middleware) {
			$this->runMiddleware();
		}

		$this->requiresDependencies($this->method, $this->args);
		call_user_func_array($this->method, $this->args);

		return 'response';
	}


	/**
	 * Checks to see if the method on the controller requires the reqeust object
	 * @param  array  $method The method being requested
	 * @param  array  $args   The args to pass to the method
	 * @return void
	 */
	protected function requiresDependencies($method, $args) {
		$reflector;

		if (is_array($method)) {
			$reflector = new ReflectionMethod($method[0], $method[1]);
    } else {
      $reflector = new ReflectionFunction($method);
    }

    $dependencies = $reflector->getParameters();
    $arguments = [];

    foreach ($dependencies as $dependency) {
    	$class = $dependency->getClass();

    	if (!is_null($class)) {
    		$binding = $this->app->getKeyFromVal($class->name);
    		$final = $this->app->resolve($binding);
    		$arguments[] = $final;
    	} else {
    		$arguments[] = null;
    	}
    }

    $counter = 0;
    foreach ($arguments as $key => $a) {
    	if ($a == null) {
    		$arguments[$key] = $this->args[$counter] ?: null;
    		$counter++;
    	}
    }

    $this->args = $arguments;

    if (isset($class->name) && ($class->name == 'Framework\Core\HTTP\Request')) {
      array_unshift($this->args, $this->request);
    }
	}


	/**
	 * Loads the routes file
	 * @return void
	 */
	protected function loadRoutes() {
		if ($this->shouldLoadRoutes) {
			require_once $this->app->basepath . '/App/HTTP/Routes.php';
		}
	}


	/**
	 * Loads the session and starts them
	 * @return void
	 */
	protected function startSessions() {
		$sessions = $this->sessions;

		foreach ($sessions as $key => $session) {
			if (!isset($_SESSION[$key])) {
				$_SESSION[$key] = $session;
			}
		}
	}


	/**
	 * Runs the required middleware
	 * @return void
	 */
	protected function runMiddleware() {
		foreach ($this->middleware as $key => $middleware) {
			$class = $this->routeMiddleware[$middleware];
			$this->app->bind(strtolower($key), $class);
			$class = $this->app->resolve($key);
			$class->handle($this->request);
		}
	}
}