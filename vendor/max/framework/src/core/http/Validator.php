<?php

namespace Framework\Core\HTTP;

use Framework\Core\HTTP\Request;

Class Validator {

	protected $errors = [];
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
		'email' => 'isEmail'

	];

	public function check(Request $request, array $array, Boolean $fast = null) {
		$this->request = $request;
		$this->errors = [];

		$fast = $fast ?: True;

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

		return true;
	}

	public function fails() {
		return $this->errors;
	}

	public function failList() {
		return array_reduce($this->errors, 'array_merge', array());
	}

	// public function unique($name) {
		
	// }

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

	public function isBoolean($name) {
		if (!is_bool($this->request->$name)) {
			$this->errors[$name][] = "Entered value for $name must be a boolean";
			return false;
		}
		return true;
	}

	public function isInteger($name) {
		if (!is_int($this->request->$name)) {
			$this->errors[$name][] = "$name must be a number";
			return false;
		}
		return true;
	}

	public function isString($name) {
		if (!is_string($this->request->$name)) {
			$this->errors[$name][] = "$name must be a string";
			return false;
		}
		return true;
	}

	public function isNull($name) {
		if (!is_null($this->request->$name)) {
			$this->errors[$name][] = "$name must be null";
			return false;
		}
		return true;
	}

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

	public function isRequired($name) {
		// echo $this->request->$name;
		// die;
		if (is_null($this->request->$name)) {
			$this->errors[$name][] = "$name is a required field";
			return false;
		}
		return true;
	}

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

	public function isUnique($name) {
		$request = $this->request->$name;
		$param = $this->param;
		$param = explode(',', $param);
		if (count($param) != 2) throw new \Exception('Incorrect Syntax for unique');
		$query = \Database::select($param[0], null, null, [$param[1] . '=' => $request]);
		$data = $query->fetchAll(\PDO::FETCH_OBJ);

		if (!empty($data)) {
			$this->errors[$name][] = "$name is not unique";
			return false;
		}
		return true;

	}

	public function isEmail($name) {
		if (!filter_var($this->request->$name, FILTER_VALIDATE_EMAIL)) {
			$this->errors[$name][] = "$name must be a valid email address";
			return false;
		}
		return true;
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