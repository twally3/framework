<?php 

namespace Framework\Core\HTTP;

class SessionRequest {

  /**
   * Has the session already been started
   * @var boolean
   */
  protected $_isStarted = false;


  /**
   * Start the session
   */
  public function __construct() {
    if (isset($_SESSION)) {
      $this->_isStarted = true;
    } else {
      $this->start();
    }
  }


  /**
   * Start the session
   * @return void
   */
  public function start() {
    if (!$this->_isStarted) {
      session_start();
      $this->_isStarted = true;
    }
  }


  /**
   * Remove a session
   * @param  string $key Name of the session
   * @return void
   */
  public function remove($key) {
    if ($this->is_set($key)) {
      unset($_SESSION[$key]);
    }
  }


  /**
   * Destroy all session data
   * @return void
   */
  public function end() {
    session_unset();
    session_destroy();
  }


  /**
   * Checks if a session has been set
   * @param  string  $key Session name
   * @return boolean      Is the session set?
   */
  public function is_set($key) {
    if (isset($_SESSION[$key])) {
      return true;
    } else {
      return false;
    }
  }


  /**
   * Sets a session
   * @param string $key   The name of the session
   * @param mixed  $value The value of the session
   */
  public function set($key, $value) {
    $_SESSION[$key] = $value;
  }


  /**
   * Get a session then remove it
   * @param  string $key Name of the session
   * @return mixed       null or session data
   */
  public function single($key) {
    $x = $this->get($key);
    $this->remove($key);
    return $x;
  }


  /**
   * Append one level into a session
   * @param  string $key   Name of the session
   * @param  mixed  $value The value to append
   * @return void
   */
  public function append($key, $value) {
    $_SESSION[$key][] = $value;
  }


  /**
   * Get a session
   * @param  string $key Reference to the session
   * @return mixed       Null or session data
   */
  public function get($key) {
    if ($this->is_set($key)) {
      return $_SESSION[$key];
    } else {
      return null;
    }
  }


  /**
   * Display the session or session by key
   * @param  string $key optional reference name
   * @return void
   */
  public function display($key = null) {
    if ($this->is_set($key)) {
      echo '<pre>';
      print_r($_SESSION[$key]);
      echo '</pre>';
    } elseif (is_null($key)) {
      echo '<pre>';
      print_r($_SESSION);
      echo '</pre>';
    }
  }
}
