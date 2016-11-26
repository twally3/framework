<?php 
// namespace Framework\Core\Helpers;
use Framework\Core\HTTP\Route as Route;

function fatalErrorShutdownHandler() {
  $last_error = error_get_last();
  if ($last_error['type'] === E_ERROR) {
    // fatal error
    customErrorCatch(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
  }
}

function customErrorCatch($num, $msg, $file, $line) {
  throw new \Exception('Error: '. $num . ' ' . $msg. ' in file '. $file. ' on line '. $line);
}

function debugArray($array = []) {
  echo '<pre>';
  print_r($array);
  echo '</pre>';
}

function dd($die) {
  echo '<pre>';
	var_dump($die);
	die;
}

function csrf_field() {
	$token = $_SESSION['csrf_token'];
	return '<input name="_token" type="hidden" value="' . $token . '">';
}

function csrf_token() {
	return $_SESSION['csrf_token'];
}

function method_field($method) {
	return '<input name="_method" type="hidden" value="' . strtolower($method) . '">';
}

function redirect($redirect) {
	return Route::redirect($redirect);
}