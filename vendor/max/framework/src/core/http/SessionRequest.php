<?php 

namespace Framework\Core\HTTP;

class SessionRequest {

  protected $_isStarted = false;

  public function __construct() {
    if (isset($_SESSION)) {
      $this->_isStarted = true;
    } else {
      $this->start();
    }
  }

  public function start() {
    if (!$this->_isStarted) {
      session_start();
      $this->_isStarted = true;
    }
  }

  public function remove($key) {
    if ($this->is_set($key)) {
      unset($_SESSION[$key]);
    }
  }

  public function end() {
    session_unset();
    session_destroy();
  }

  public function is_set($key) {
    if (isset($_SESSION[$key])) {
      return true;
    } else {
      return false;
    }
  }

  public function set($key, $value) {
    $_SESSION[$key] = $value;
  }

  public function single($key) {
    $x = $this->get($key);
    $this->remove($key);
    return $x;
  }

  public function append($key, $value) {
    $_SESSION[$key][] = $value;
  }

  public function get($key) {
    if ($this->is_set($key)) {
      return $_SESSION[$key];
    } else {
      return null;
    }
  }

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
