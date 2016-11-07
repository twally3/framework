<?php

function debugArray($array = []) {
  echo '<pre>';
  print_r($array);
  echo '</pre>';
}

function customErrorCatch($num, $msg, $file, $line) {
  throw new Exception('Error: '. $num . ' ' . $msg. ' in file '. $file. ' on line '. $line);
}

function csrf_token() {
	$token = $_SESSION['csrf_token'];
	return '<input name="_token" type="hidden" value="' . $token . '">';
}

function methodPut() {
	return '<input name="_method" type="hidden" value="put">';
}

function methodDelete() {
	return '<input name="_method" type="hidden" value="delete">';
}