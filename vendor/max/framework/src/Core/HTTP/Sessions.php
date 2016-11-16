<?php namespace Framework\Core\HTTP;

class Session {

  public static $_isStarted = false;

  public static function start() {
    if (!self::$_isStarted) {
      session_start();
      self::$_isStarted = true;
    }
  }

  public static function unset($key) {
    if (self::isset($key)) {
      unset($_SESSION[$key]);
    }
  }

  public static function end() {
    session_unset();
    session_destroy();
  }

  public static function isset($key) {
    if (isset($_SESSION[$key])) {
      return true;
    } else {
      return false;
    }
  }

  public static function set($key, $value) {
    $_SESSION[$key] = $value;
  }

  public static function append($key, $value) {
    $_SESSION[$key][] = $value;
  }

  public static function get($key) {
    if (self::isset($key)) {
      return $_SESSION[$key];
    } else {
      return null;
    }
  }

  public static function display($key = null) {
    if (self::isset($key)) {
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
