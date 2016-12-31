<?php

namespace Framework\Core\Database;

class Table {

  /**
   * The query elements
   * @var array
   */
  public $array = [];

  /**
   * The primary key field
   * @var string
   */
  public $key = '';

  /**
   * The database engine
   * @var string
   */
  public $engine = '';


  /**
   * The last field targeted
   * @var string
   */
  public $lastField = '';


  /**
   * Fields that have passed the not null method
   * @var array
   */
  public $notNulls = [];


  /**
   * Sets the engine type of the table
   * @param  string $engine The name of the database engine
   * @return void
   */
  function engine($engine) {
    $this->engine = " ENGINE = {$name}";
  }


  /**
   * Adds the timestamp variables for ORM
   * @return void
   */
  function timestamps() {
    $this->array['created_at'] = " VARCHAR(128) NOT NULL";
    $this->array['updated_at'] = " VARCHAR(128) NOT NULL";
  }

  // Variable types


  /**
   * Create a varchar column
   * @param  string  $name   Column name
   * @param  integer $length Data length
   * @return $this            Returns the current instance of the class
   */
  function varchar($name, $length) {
    $this->array[$name] = " VARCHAR($length)";
    $this->lastField = $name;
    return $this;
  }


  /**
   * Create a text column
   * @param  string $name Name of the column
   * @return $this         Current instance of the class
   */
  function text($name) {
    $this->array[$name] = ' TEXT';
    $this->lastField = $name;
    return $this;
  }


  /**
   * Creates an INT column
   * @param  string  $name   Name of the column
   * @param  integer $length Data length
   * @return $this            Current instance of the class
   */
  function int($name, $length=null) {
    $this->array[$name] = " INT";
    $this->array[$name] .= is_null($length) ? '' : "($length)";
    $this->lastField = $name;
    return $this;
  }


  /**
   * Creates a bool column
   * @param  string $name Name of the column
   * @return $this
   */
  function bool($name) {
    $this->array[$name] = " BOOLEAN";
    $this->lastField = $name;
    return $this;
  }


  /**
   * Creates a floating point field
   * @param  string  $name   Name of the column
   * @param  integer $length Length of the data
   * @return $this
   */
  function float($name, $length) {
    $this->array[$name] = " FLOAT($length)";
    $this->lastField = $name;
    return $this;
  }

  // constraints


  /**
   * Makes a specified field not null
   * @param  string $name Name of the column
   * @return $this
   */
  function notNull($name=null) {
    $name = is_null($name) ? $this->lastField : $name;
    $this->array[$name] = $this->array[$name] . " NOT NULL";
    $this->notNulls[] = $name;
    return $this;
  }


  /**
   * Ensures a given column is unique
   * @param  string $name Name of the column
   * @return [type]
   */
  function unique($name=null) {
    $name = is_null($name) ? $this->lastField : $name;
    $this->array[$name] = $this->array[$name] . " UNIQUE";
    return $this;
  }


  /**
   * Sets the default value of a field
   * @param  string $name    Name of the column
   * @param  string $default The default value
   * @return $this
   */
  function defaultVal($name, $default) {
    if (!in_array($name, $this->notNulls)) {
      $this->array[$name] .= " NULL";
    }
    $this->array[$name] .= " DEFAULT" . $default;
    return $this;
  }


  /**
   * Makes an incrimenting field
   * @param  string $name Name of the column
   * @return $this
   */
  function incriment($name=null) {
    $name = is_null($name) ? $this->lastField : $name;
    $this->array[$name] = $this->array[$name] . " AUTO_INCREMENT";
    return $this;
  }


  /**
   * Makes a value the primary key
   * @param  string $name Name of the column
   * @return $this
   */
  function primary($name=null) {
    $name = is_null($name) ? $this->lastField : $name;
    $this->key = " PRIMARY KEY (`$name`)";
    return $this;
  }

  /**
   * Creates the SQL query
   * @return string The final SQL query
   */
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
