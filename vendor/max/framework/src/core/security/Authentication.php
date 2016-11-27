<?php

namespace Framework\Core\Security;

use \Database;
use \PDO;
use \Route;

class Authentication {

	private $algorithm = PASSWORD_DEFAULT;

	public function attempt($array) {
		$email = $array['email'];
		$password = $array['password'];
		$remember = False;

		if (isset($array['remember'])) {
			if ($array['remember'] == true) {
				$remember = True;
			}
		}

		$remember = (isset($array['remember'])) ? $array['remember'] : false;

		if ($this->user_exists('email', $email)) {
			$query = Database::select('users', null, null, ['email = ' => $email]);
			$data = $query->fetchAll(PDO::FETCH_OBJ)[0];
			$hash = $data->password;

			if ($this->verify($password, $hash)) {

				$_SESSION['user'] = $data->id;
				$_SESSION['token'] = md5($data->created_at . $data->username);

				if ($remember) {
					$expire = time()+30*24*60*60;

					setcookie('cookieAuth[user]', $_SESSION['user'], $expire, '', '', '', true);
					setcookie('cookieAuth[authID]', $_SESSION['token'], $expire, '', '', '', true);
				}

				if ($this->needs_rehash($hash)) {
					$this->hash($password);
				}
				return true;
			}
			return false;

		}
		return false;
	}

	public function user() {
		return !is_null($this->id()) ? $this->get_user_data($this->id()) : null;
	}

	public function logout() {
		unset($_SESSION['user']);
		unset($_SESSION['token']);

		setcookie('cookieAuth[user]', '', -3600, '', '', '', true);
		setcookie('cookieAuth[authID]', '', -3600, '', '', '', true);

		if (!isset($_SESSION['user'])) {
			return (!isset($_COOKIE['cookieAuth[user]'])) ? true : false;
		} else {
			return false;
		}
	}

	public function check() {
		$needSession = false;

		if (isset($_SESSION['user']) && isset($_SESSION['token'])) {
			$user = $_SESSION['user'];
			$token = $_SESSION['token'];
		} else if (isset($_COOKIE['cookieAuth']['user']) && isset($_COOKIE['cookieAuth']['authID'])) {
			$user = $_COOKIE['cookieAuth']['user'];
			$token = $_COOKIE['cookieAuth']['authID'];
			$needSession = true;
		} else {
			$this->logout();
			return false;
		}

		if (!$this->user_exists('id', $user)) {
			$this->logout();
			return false;
		}

		$query = Database::select('users', null, null, ['id =' => $user]);
		$data = $query->fetchAll(PDO::FETCH_OBJ)[0];

		if ($token == md5($data->created_at . $data->username)) {
			if ($needSession) {
				$_SESSION['user'] = $data->id;
				$_SESSION['token'] = md5($data->created_at . $data->username);
			}

			return true;
		}

		$this->logout();
		return false;
	}

	public function hash($password) {
		return password_hash($password, $this->algorithm);
	}

	// public function register($request) {

	// }

	public function get_user_data($user) {
		$numArgs = func_num_args();
		$args = func_get_args();


		if ($numArgs > 1) {
			unset($args[0]);
		} else {
			$args = null;
		}

		$query = Database::select('users', $args, null, ['id =' => $user]);
		$data = $query->fetchAll(PDO::FETCH_OBJ);

		return !empty($data) ? $data[0] : null;

	}

	public function user_exists($field, $value) {
		$query = Database::select('users', null, null, [$field . '=' => $value]);
		$data = $query->fetchAll(PDO::FETCH_OBJ);
		return !empty($data) ? true : false;
	}

	public function id() {
		return isset($_SESSION['user']) ? $_SESSION['user'] : null;
	}

	protected function verify($password, $hash) {
		return password_verify($password, $hash);
	}

	protected function needs_rehash($hash) {
		return password_needs_rehash($hash, $this->algorithm);
	}

	protected function get_info($hash) {
		return password_get_info($hash);
	}

	public function find_cost($baseline = 8) {
		$target = 0.05;
		$cost = $baseline;

		do {
			$cost++;
			$start = microtime(true);
			password_hash('test', $this->algorithm, ['cost' => $cost]);
			$end = microtime(true);
		} while ( ($end - $start) < $target );

		return $cost;
	}

}