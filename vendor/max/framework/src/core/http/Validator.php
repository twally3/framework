<?php

namespace Framework\Core\HTTP;

use Framework\Core\HTTP\Request;

Class Validator {

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

	public function check(Request $request, array $array) {
		$this->request = $request;

		foreach ($array as $key => $value) {
			$lists = explode('|', $value);
			foreach ($lists as $list) {
				$this->param = isset(explode(':', $list)[1]) ? explode(':', $list)[1] : null;
				$list = explode(':', $list)[0];

				if (!array_key_exists($list, $this->keywords)) return false;
				$method = $this->keywords[$list];
				if (!$this->$method($key)) return false;
			}
		}

		return true;
	}

	// public function unique($name) {
		
	// }

	public function max($name) {
		if (is_null($this->param)) return false;
		if (is_string($this->request->$name) && strlen($this->request->$name) > $this->param) return false;
		if (is_int($this->request->$name) && $this->request->$name > $this->param) return false;
		return true;
	}

	public function min($name) {
		if (is_null($this->param)) return false;
		if (is_string($this->request->$name) && strlen($this->request->$name) < $this->param) return false;
		if (is_int($this->request->$name) && $this->request->$name < $this->param) return false;
		return true;
	}

	public function isBoolean($name) {
		if (!is_bool($this->request->$name)) return false;
		return true;
	}

	public function isInteger($name) {
		if (!is_int($this->request->$name)) return false;
		return true;
	}

	public function isString($name) {
		if (!is_string($this->request->$name)) return false;
		return true;
	}

	public function isNull($name) {
		if (!is_null($this->request->$name)) return false;
		return true;
	}

	public function isBefore($name) {
		if (is_null($this->param)) return false;
		if ($this->request->$name < $this->param) return false;

		return true;
	}

	public function isAfter($name) {
		if (is_null($this->param)) return false;
		if ($this->request->$name > $this->param) return false;

		return true;
	}

	public function isAccepted($name) {
		if (!is_bool($this->request->$name)) return false;
		if ($this->request->$name == false) return false;
		return true;
	}

	public function isRequired($name) {
		// echo $this->request->$name;
		// die;
		if (is_null($this->request->$name)) return false;
		return true;
	}

	public function doesMatch($name) {
		$param = $this->param;
		if (is_null($this->request->$name)) return false;
		if ($this->request->$name != $this->request->$param) return false;
		return true;
	}

	public function isUnique($name) {
		$request = $this->request->$name;
		$param = $this->param;
		$param = explode(',', $param);
		if (count($param) != 2) return false;
		$query = \Database::select($param[0], null, null, [$param[1] . '=' => $request]);
		$data = $query->fetchAll(\PDO::FETCH_OBJ);

		if (!empty($data)) return false;
		return true;

	}

	public function isEmail($name) {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
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