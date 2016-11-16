<?php namespace Framework\Core\Database;

class Table {

  public $query = '';
  public $array = [];
  public $name = '';
  public $key = '';
  public $engine = '';

  public $lastField = '';
  public $nulls = [];

  function engine($engine) {
    $this->engine = " ENGINE = {$name}";
  }

  function timestamps() {
    $this->array['created_at'] = " VARCHAR(128) NOT NULL";
    $this->array['updated_at'] = " VARCHAR(128) NOT NULL";
  }

  //Types

  function varchar($name, $length) {
    $this->array[$name] = " VARCHAR($length)";
    $this->lastField = $name;
    return $this;
  }

  function text($name) {
    $this->array[$name] = ' TEXT';
    $this->lastField = $name;
    return $this;
  }

  function int($name, $length=null) {
    $this->array[$name] = " INT";
    $this->array[$name] .= is_null($length) ? '' : "($length)";
    $this->lastField = $name;
    return $this;
  }

  function bool($name) {
    $this->array[$name] = " BOOLEAN";
    $this->lastField = $name;
    return $this;
  }

  function float($name, $length) {
    $this->array[$name] = " FLOAT($length)";
    $this->lastField = $name;
    return $this;
  }

  // constraints

  function notNull($name=null) {
    $name = is_null($name) ? $this->lastField : $name;
    $this->array[$name] = $this->array[$name] . " NOT NULL";
    $this->nulls[] = $name;
    return $this;
  }

  function unique($name=null) {
    $name = is_null($name) ? $this->lastField : $name;
    $this->array[$name] = $this->array[$name] . " UNIQUE";
    return $this;
  }

  function defaultVal($name, $default) {
    if (!in_array($name, $this->nulls)) {
      $this->array[$name] .= " NULL";
    }
    $this->array[$name] .= " DEFAULT" . $default;
    return $this;
  }

  function incriment($name=null) {
    $name = is_null($name) ? $this->lastField : $name;
    $this->array[$name] = $this->array[$name] . " AUTO_INCREMENT";
    return $this;
  }

  function primary($name=null) {
    $name = is_null($name) ? $this->lastField : $name;
    $this->key = " PRIMARY KEY (`$name`)";
    return $this;
  }


  function create() {
    $string = $this->query;

    foreach ($this->array as $key => $value) {
      $string .= " `" . $key . "` " . $value . " ,";
    }


    $string .= $this->key;

    $string = rtrim($string, ',');
    return $string;
  }


}
