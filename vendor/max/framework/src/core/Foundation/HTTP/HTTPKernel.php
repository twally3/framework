<?php

namespace Framework\Core\Foundation\HTTP;

use Framework\Core\Foundation\Application;
use Framework\Core\HTTP\Request;
use Framework\Core\HTTP\Router;

use ReflectionFunction;
use ReflectionMethod;

Class HTTPKernel {

	protected $config;
	protected $app;
	protected $router;

	protected $method;
	protected $args;
	protected $middleware = null;

	protected $request;

	public function __construct(Application $app, Router $router) {
		$this->app = $app;
		$this->router = $router;

		$this->startSessions();

		// debugArray($_SESSION);

		$this->loadRoutes();
	}

	public function handle(Request $request) {
		$this->request = $request;
		
		list($this->method, $this->args, $this->middleware) = $this->router->submit();

		if ($this->middleware) {
			$this->runMiddleware();
		}

		$this->requiresRequest($this->method, $this->args);
		call_user_func_array($this->method, $this->args);

		return 'response';
	}

	protected function requiresRequest($method, $args) {
		$reflector;

		if (is_array($method)) {
			$reflector = new ReflectionMethod($method[0], $method[1]);
	    } else {
	      $reflector = new ReflectionFunction($method);
	    }

	    $dependencies = $reflector->getParameters();
	    $class = isset($dependencies[0]) ? $dependencies[0]->getClass() : null;
	    
	    if (isset($class->name) && ($class->name == 'Framework\Core\HTTP\Request')) {
	      array_unshift($this->args, $this->request);
	    }
	}

	protected function loadRoutes() {
		require_once $this->app->basepath . '/app/http/routes.php';
	}

	protected function startSessions() {
		$sessions = require_once $this->app->basepath . '/app/bootstrap/sessions.php';

		foreach ($sessions as $key => $session) {
			if (!isset($_SESSION[$key])) {
				$_SESSION[$key] = $session;
			}
		}
	}

	protected function runMiddleware() {
		foreach ($this->middleware as $key => $middleware) {
			$class = $this->routeMiddleware[$middleware];
			$this->app->bind(strtolower($key), $class);
			$class = $this->app->resolve($key);
			$class->handle($this->request);
			

			// print_r($class);
			// $class->handle($this->request);
		}
	}
}