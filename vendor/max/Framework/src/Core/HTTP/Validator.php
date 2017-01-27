<?php

namespace Framework\Core\HTTP;

use Framework\Core\HTTP\Request;
use Framework\Core\Database\Database;

Class Validator {

	/**
	 * The list of errors that have occured
	 * @var array
	 */
	protected $errors = [];

	/**
	 * Validation types
	 * @var array
	 */
	protected $keywords = [

		// 'unique' => 'unique',
		'max' => 'max',
		'min' => 'min',
		'bool' => 'isBoolean',
		'int' => 'isInteger',
		'string' => 'isString',
		'null' => 'isNull',
		'before' => 'isBefore',
		'after' => 'isAfter',
		'accepted' => 'isAccepted',
		'required' => 'isRequired',
		'matches' => 'doesMatch',
		'unique' => 'isUnique',
		'email' => 'isEmail',
		'alphanumeric' => 'isAlph'

	];


	/**
	 * Loads and stores dependencies
	 * @param Database $db Database connection object
	 */
	public function __construct(Database $db) {
		$this->db = $db;
	}

	/**
	 * Check the request against the rules
	 * @param  Request      $request The Request object
	 * @param  array        $array   The Rules array
	 * @param  Boolean|null $fast    Quit on the first error
	 * @return boolean               Did the rules match
	 */
	public function check(Request $request, array $array, $fast = true) {
		$this->request = $request;
		$this->errors = [];

		foreach ($array as $key => $value) {
			$lists = explode('|', $value);
			foreach ($lists as $list) {
				$this->param = isset(explode(':', $list)[1]) ? explode(':', $list)[1] : null;
				$list = explode(':', $list)[0];

				if (!array_key_exists($list, $this->keywords)) throw new \Exception("$list ");
				$method = $this->keywords[$list];
				if (!$this->$method($key)) {
					if ($fast) return false;
				}
			}
		}

		return empty($this->errors) ? true : false;
	}


	/**
	 * The raw errors array
	 * @return array The list of errors
	 */
	public function fails() {
		return $this->errors;
	}


	/**
	 * The list of fails
	 * @return array The fail list
	 */
	public function failList() {
		return array_reduce($this->errors, 'array_merge', array());
	}


	/**
	 * Checks a value doesnt have a length greater than max
	 * @param  string $name Name of property
	 * @return boolean      Success
	 */
	public function max($name) {
		if (is_null($this->param)) {
			throw new \Exception("Parameter is not set for Max");
			return false;
		}
		if (is_string($this->request->$name) && strlen($this->request->$name) > $this->param) {
			$this->errors[$name][] = "$name must be less than " . $this->param . " in length";
			return false;
		}
		if (is_int($this->request->$name) && $this->request->$name > $this->param) {
			$this->errors[$name][] = "$name must be less than " . $this->param . " in length";
			return false;
		}
		return true;
	}


	/**
	 * Checks a value isnt shorter than
	 * @param  string $name Reference name
	 * @return Boolean      Success
	 */
	public function min($name) {
		if (is_null($this->param)) {
			throw new \Exception("Parameter is not set for Min");
			return false;
		}
		if (is_string($this->request->$name) && strlen($this->request->$name) < $this->param) {
			$this->errors[$name][] = "$name must be longer than " . $this->param . " in length";
			return false;
		}
		if (is_int($this->request->$name) && $this->request->$name < $this->param) {
			$this->errors[$name][] = "$name must be less than " . $this->param . " in length";
			return false;
		}
		return true;
	}


	/**
	 * Checks if a value is a bool
	 * @param  string  $name The reference name
	 * @return boolean       Success
	 */
	public function isBoolean($name) {
		if (!is_bool($this->request->$name)) {
			$this->errors[$name][] = "Entered value for $name must be a boolean";
			return false;
		}
		return true;
	}


	/**
	 * Checks if a value is an integer
	 * @param  string  $name Reference name
	 * @return boolean       Success
	 */
	public function isInteger($name) {
		if (!is_int($this->request->$name)) {
			$this->errors[$name][] = "$name must be a number";
			return false;
		}
		return true;
	}


	/**
	 * Checks if the value is a string
	 * @param  string  $name Reference name
	 * @return boolean       Success
	 */
	public function isString($name) {
		if (!is_string($this->request->$name)) {
			$this->errors[$name][] = "$name must be a string";
			return false;
		}
		return true;
	}


	/**
	 * Checks if a values is null
	 * @param  string  $name Reference Name
	 * @return boolean       Success
	 */
	public function isNull($name) {
		if (!is_null($this->request->$name)) {
			$this->errors[$name][] = "$name must be null";
			return false;
		}
		return true;
	}


	/**
	 * Checks if a date is before a given time
	 * @param  string  $name Reference name
	 * @return boolean       Success
	 */
	public function isBefore($name) {
		if (is_null($this->param)) {
			throw new \Exception("Parameter is not set for before");
			return false;
		}
		if ($this->request->$name < $this->param) {
			$this->errors[$name][] = "$name must be before " . $this->param;
			return false;
		}

		return true;
	}


	/**
	 * Checks if a date is after
	 * @param  string  $name Reference name
	 * @return boolean       Success
	 */
	public function isAfter($name) {
		if (is_null($this->param)) {
			throw new \Exception("Parameter is not set for after");
			return false;
		}
		if ($this->request->$name > $this->param) {
			$this->errors[$name][] = "$name must be after " . $this->param;
			return false;
		}

		return true;
	}


	/**
	 * Checks a value is true
	 * @param  String  $name Reference name
	 * @return boolean       Success
	 */
	public function isAccepted($name) {
		if (!is_bool($this->request->$name)) {
			throw new \Exception("$name is not a boolean value");
			return false;
		}
		if ($this->request->$name == false) {
			$this->errors[$name][] = "$name should be true";
			return false;
		}
		return true;
	}


	/**
	 * Checks a value is not empty
	 * @param  string  $name Reference name
	 * @return boolean       Success
	 */
	public function isRequired($name) {
		// echo $this->request->$name;
		// die;
		if (is_null($this->request->$name)) {
			$this->errors[$name][] = "$name is a required field";
			return false;
		}
		return true;
	}


	/**
	 * Checks if two properties match
	 * @param  string $name Reference name
	 * @return Boolean      Success
	 */
	public function doesMatch($name) {
		$param = $this->param;
		if (is_null($this->request->$name)) {
			$this->errors[$name][] = "$name is not set";
			return false;
		}
		if ($this->request->$name != $this->request->$param) {
			$this->errors[$name][] = "$name field does not match";
			return false;
		}
		return true;
	}


	/**
	 * Checks if a value is unique in DB
	 * @param  string  $name Reference name
	 * @return boolean       Success
	 */
	public function isUnique($name) {
		$request = $this->request->$name;
		$param = $this->param;
		$param = explode(',', $param);
		if (count($param) != 2) throw new \Exception('Incorrect Syntax for unique');
		$query = $this->db->select($param[0], null, null, [$param[1] . '=' => $request]);
		$data = $query->fetchAll(\PDO::FETCH_OBJ);
		
		if (!empty($data)) {
			$this->errors[$name][] = "$request is taken";
			return false;
		}
		return true;

	}


	/**
	 * Checks if the prop is an email
	 * @param  string  $name Reference Name
	 * @return boolean       Success
	 */
	public function isEmail($name) {
		if (!filter_var($this->request->$name, FILTER_VALIDATE_EMAIL)) {
			$this->errors[$name][] = "$name must be a valid email address";
			return false;
		}
		return true;
	}


	/**
	 * Only allows alphanumeric chars
	 * @param  string  $name Reference Name
	 * @return boolean       Success
	 */
	public function isAlph($name) {
		$request = $this->request->$name;
		if (is_null($request) || ctype_alnum(str_replace(' ', '', $request))) return true;
		$this->errors[$name][] = "$name must be only contain alphanumeric characters";
		return false;
		
	}
}

/*
* - Unique
* - Max:255, Min:255
* - Boolean
* - Integer
* - String
* - Nul
* - before:timestamp, after:timestamp
* - Accepted
* - Required
*/