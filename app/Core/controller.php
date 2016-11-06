<?php

class Controller extends Tea {

  public function model($model) {
    require_once '../app/models/' . $model . '.php';
    return new $model();
  }

  public function rawView($view, $data = []) {
    require_once '../app/views/' . $view . '.php';
  }

  public function json($array) {
    echo json_encode($array);
  }
}
