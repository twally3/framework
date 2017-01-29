<?php

namespace Framework\Core\Security;

use Framework\Core\Database\Database as Database;
use \PDO;

class Authentication {

	/**
	 * The current password hashing algorithm
	 * @var string
	 */
	private $algorithm = PASSWORD_DEFAULT;


	/**
	 * Load the database dependency
	 * @param Framework\Core\Database\Database $db The database class
	 */
	public function __construct(Database $db, JWT $jwt) {
		$this->db = $db;
		$this->jwt = $jwt;
	}


	/**
	 * Try to log the user in
	 * @param  array $array email, password and remember
	 * @return boolean      Success
	 */
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
			$query = $this->db->select('users', null, null, ['email = ' => $email]);
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


	/**
	 * Return the user information
	 * @return object User object
	 */
	public function user() {
		return !is_null($this->id()) ? $this->get_user_data($this->id()) : null;
	}


	/**
	 * Log out the user
	 * @return boolean Success
	 */
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


	/**
	 * Check a user is authenticated
	 * @return boolean Success
	 */
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

		$query = $this->db->select('users', null, null, ['id =' => $user]);
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


	/**
	 * Hash a given password
	 * @param  string $password Password to hash
	 * @return string           Hashed password
	 */
	public function hash($password) {
		return password_hash($password, $this->algorithm);
	}


	/**
	 * Generate an auth token
	 * @return void
	 */
	public function token() {
		$token = [];
		$token['id'] = $this->id();
		setcookie('jwt_token', $this->jwt->encode($token, SITE_KEY), 0, '', '', '', false);
	}

	// public function register($request) {

	// }


	/**
	 * Get the user data of a given userid
	 * @param  integer $user The user id
	 * @return mixed         The user data
	 */
	public function get_user_data($user) {
		$numArgs = func_num_args();
		$args = func_get_args();


		if ($numArgs > 1) {
			unset($args[0]);
		} else {
			$args = null;
		}

		$query = $this->db->select('users', $args, null, ['id =' => $user]);
		$data = $query->fetchAll(PDO::FETCH_OBJ);

		return !empty($data) ? $data[0] : null;

	}


	/**
	 * Check if a user exists
	 * @param  string $field The field to check for
	 * @param  string $value The value of the field
	 * @return boolean       Success
	 */
	public function user_exists($field, $value) {
		$query = $this->db->select('users', null, null, [$field . '=' => $value]);
		$data = $query->fetchAll(PDO::FETCH_OBJ);
		return !empty($data) ? true : false;
	}


	/**
	 * Get the current user id
	 * @return mixed null or user id
	 */
	public function id() {
		return isset($_SESSION['user']) ? $_SESSION['user'] : null;
	}


	/**
	 * Check a password hash
	 * @param  string $password raw password
	 * @param  string $hash     Hashed password
	 * @return boolean          Success
	 */
	protected function verify($password, $hash) {
		return password_verify($password, $hash);
	}


	/**
	 * Checks if the password needs rehashing
	 * @param  string $hash hashed password
	 * @return boolean      Does it need rehashing?
	 */
	protected function needs_rehash($hash) {
		return password_needs_rehash($hash, $this->algorithm);
	}


	/**
	 * Return the hasing informaton
	 * @param  string $hash Hashed password
	 * @return array       	The hash information
	 */
	protected function get_info($hash) {
		return password_get_info($hash);
	}


	/**
	 * Find the cost of the hash algorithm for the server
	 * @param  integer $baseline The attempted check value
	 * @return integer           The final cost
	 */
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