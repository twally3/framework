<?php

namespace Framework\Core\HTTP;

use Framework\Core\Foundation\Application;
// use Framework\Core\HTTP\FileRequest;

Class Request {
	protected $app;
	protected $file;

	protected $requestData;

	// Stores the request data in the $requestData array.
	public function __construct(Application $app) {
		$this->app = $app;
		// $this->file = $file;

		$this->requestData = array_merge($_GET, $_POST, $_FILES);
	}

	// Allows accessing of keys as properties.
	public function __get($params) {
		if (!empty($this->requestData[$params])) {
			return $this->requestData[$params];
		} else {
			return null;
		}
	}

	// Used to get the requests Method (GET or POST)
	public function getMethod() {
		return $_SERVER['REQUEST_METHOD'];
	}

	// Gets all the GET and POST requests
	public function all() {
		return $this->requestData;
	}

	// returns the url after the domain 
	public function path() {
		return parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
	}

	// Check the Referrer url. Can use * for param values
	public function is($string) {
		$string = "#{$string}#";
		$string = preg_replace('#\/#', '\/', $string);
		$string = preg_replace('#\*#', '.+', $string);

		if (preg_match($string, $this->path())) {
			return 'true';
		} else {
			return 'false';
		}
	}

	// Tests the Method type
	public function isMethod($method) {
		return $this->getMethod() == strtoupper($method);
	}

	//get the data with a specific key from the array or return a defalt value
	public function input($key, $default = null) {
		return isset($this->requestData[$key]) ? $this->requestData[$key] : $default;
	}

	// Only get specific keyed data
	public function only() {
		$results = [];
		$argCount = func_num_args();
		$args = func_get_args();

		if ($argCount == 0) {
			return null;
		} if ($argCount == 1 && is_array($args[0])) {
			$array = $args[0];
		} else {
			$array = $args;
		}

		foreach ($array as $value) {
			$results[$value] = $this->requestData[$value];
		}

		return $results;
	}	

	// All data except [params]
	public function exclude() {
		$results = [];
		$argCount = func_num_args();
		$args = func_get_args();

		if ($argCount == 1 && is_array($args[0])) {
			$array = $args[0];
		} else {
			$array = $args;
		}

		foreach ($this->requestData as $key => $value) {
			if (!in_array($key, $array)) {
				$results[$key] = $this->requestData[$key];
			}
		}

		return $results;
	}

	//Check if a certain value exists by key
	public function has($name) {
		return (isset($this->requestData[$name])) ? true : false;
	}

	// ---------- Session section ----------

	//Write data to session
	public function flash() {
		$_SESSION['flash']['request'] = $this->requestData;
	}

	// Flash only params
	public function flashOnly() {
		$results = [];
		$argCount = func_num_args();
		$args = func_get_args();

		if ($argCount == 0) {
			return null;
		} if ($argCount == 1 && is_array($args[0])) {
			$array = $args[0];
		} else {
			$array = $args;
		}

		foreach ($array as $value) {
			$results[$value] = $this->requestData[$value];
		}

		$_SESSION['flash']['request'] = $results;
	}

	// Flash everything excluding params
	public function flashExclude() {
		$results = [];
		$argCount = func_num_args();
		$args = func_get_args();

		if ($argCount == 1 && is_array($args[0])) {
			$array = $args[0];
		} else {
			$array = $args;
		}

		foreach ($this->requestData as $key => $value) {
			if (!in_array($key, $array)) {
				$results[$key] = $this->requestData[$key];
			}
		}

		$_SESSION['flash']['request'] = $results;
	}

	// Get all or a subset of data from the Flash>request session
	public static function old($name=null) {
		if (!is_null($name)) {
			if (isset($_SESSION['flash']['request'][$name])) {
				$session = $_SESSION['flash']['request'][$name];
				unset($_SESSION['flash']['request'][$name]);
				return $session;
			} else {
				return '';
			}
		} else {
			$_SESSION['flash']['request'];
		}
		// return (!is_null($name) && isset($_SESSION['flash']['request'])) ? $_SESSION['flash']['request'][$name] : $_SESSION['flash']['request'];
	}

	// Removes everything from the current flashed session
	public function emptyFlash() {
		unset($_SESSION['flash']['request']);
	}

	public function server($name) {
		return $name ? $_SERVER[$name] : $_SERVER;
	}

	// ---------- File section -------------

	// Return the File array with the name
	public function file($name) {
		$results = [];
		if (is_array($_FILES[$name]['name'])) {
			$files = $this->diverse_array($_FILES[$name]);
			foreach ($files as $file) {
				$class = $this->app->resolve('filerequest', [$file]);
				// $class->construct($file);
				$results[] = $class;
			}
			return $results;
		} else {
			// return new File($_FILES[$name]);
			$class = $this->app->resolve('filerequest', [$_FILES[$name]]);
			return $class;
		}
	}

	// Checks if the array has the File by name
	public function hasFile($name) {
		return ($_FILES[$name]['name'][0] != '') ? true : false;
	}

	// Makes multiple file arrays simpler. Not for human consumption!
	public function diverse_array($vector) { 
		$result = array(); 
			foreach($vector as $key1 => $value1) 
				foreach($value1 as $key2 => $value2) 
					$result[$key2][$key1] = $value2; 
			return $result; 
	}
}