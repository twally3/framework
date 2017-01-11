<?php

namespace Framework\Core\Database;

use \PDO as PDO;
use Framework\Core\Foundation\Application;

class Database {

  /**
   * Storec database connection
   * @var Object
   */
  public $_connection;


  /**
   * Connects to the database and stores the connection to the class
   */
  public function __construct() {
    try {
      $handler = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
      $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      printf ("Connect failed %s\n", $e->getMessage());
      exit();
    }

    $this->_connection = $handler;
  }


  /**
   * Insert data into the given table
   * @param  string $table The name of the table
   * @param  array  $data  The assoc array of data
   * @return Boolean       Success
   */
  public function insert($table, $data) {
    list($fields, $placeholders, $values) = $this->prep_values($data);

    $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
    $query = $this->_connection->prepare($sql);

    return $query->execute($values) ? true : false;
  }


  /**
   * Update given data in a given table
   * @param  string $table     The name of the table
   * @param  array  $data      Assoc array of data to set
   * @param  array  $where     Assoc array of where clauses
   * @param  array  $operators Array of operators [AND, OR, AND]
   * @return Boolean           Success
   */
  public function update($table, $data, $where, $operators=array()) {
    list($fields, $placeholders, $values) = $this->prep_values($data, 'update');
    list($where_clause, $where_values) = $this->prep_where($where, $operators);

    $values = array_merge($values, $where_values);

    $sql = "UPDATE {$table} SET {$placeholders} WHERE {$where_clause}";

    $query = $this->_connection->prepare($sql);

    return $query->execute($values) ? true : false;
  }


  /**
   * Delete rows from a given table where given data
   * @param  string $table     Name of the target table
   * @param  array  $where     Assoc array of where values
   * @param  array  $operators Array of operators
   * @return Boolean           Success
   */
  public function delete($table, $where, $operators=array()) {
    list($where_clause, $where_values) = $this->prep_where($where, $operators);

    $sql = "DELETE FROM {$table} WHERE {$where_clause}";
    $query = $this->_connection->prepare($sql);

    return $query->execute($where_values) ? true : false;
  }


  /**
   * Selects data from the database
   * @param  string $table      The name of the target table
   * @param  array  $headings   Array of headings, [h1, h2, h3]. Defaults to *
   * @param  string $join       String join statement
   * @param  array  $where      Assoc array of where values
   * @param  array  $operators  Array of operators [AND, OR, AND]
   * @param  string $additional Additional apended information ORDER BY etc.
   * @return PDO                Returns a PDO query object
   */
  public function select($table, $headings=null, $join=null, $where=array(), $operators=array(), $additional='') {
    if (is_array($headings)) {
      $headings = implode(',', $headings);
    } else if (is_string($headings)) {
      $headings = $headings;
    } else {
      $headings = '*';
    }

    $where = is_null($where) ? array() : $where;
    $operators = is_null($operators) ? array() : $operators;
    $join = is_null($join) ? '' : $join;

    list($where_clause, $where_values) = $this->prep_where($where, $operators);
    $where_values = !empty($where_values) ? $where_values : array();

    $sql = "SELECT {$headings} FROM {$table}";

    if (!empty($join)) {
      $sql .= " " . $join . " ";
    }

    if (!empty($where)) {
      $sql .= " WHERE {$where_clause}";
    }

    $sql .= " " . $additional;

    $query = $this->_connection->prepare($sql);
    $query->execute($where_values);

    return $query;
  }


  /**
   * Runs a standard, non-escaped query
   * @param  string $sql SQL query string
   * @return PDO         Returns the PDO query object
   */
  public function query($sql) {
    return $this->_connection->query($sql);
  }


  /**
   * Quotes a given string
   * @param  string $string Input string to be quoted
   * @return string         Quoted string
   */
  public function quote($string) {
    return $this->_connection->quote($string);
  }


  /**
   * Gets the last insert id from the database.
   * @return integer The ID from the database
   */
  public function last_id() {
    return $this->_connection->lastInsertId();
  }


  /**
   * Prepares values for insert
   * @param  array $data  Assoc array of insert data
   * @param  string $type insert or update for syntax =? or ?,
   * @return array        Array of $fields, $placeholders and $values
   */
  public function prep_values($data, $type='insert') {
    $fields = '';
    $placeholders = '';
    $values = [];

    foreach ($data as $field => $value) {
      $fields .= "{$field},";
      $values[] = $value;

      if ($type == 'update') {
        $placeholders .= $field . '=?,';
      } else {
        $placeholders .= '?,';
      }
    }

    $fields = substr($fields, 0, -1);
    $placeholders = substr($placeholders, 0, -1);

    // $values = $this->htmlchars($values);

    return array($fields, $placeholders, $values);
  }


  /**
   * Perpares where values
   * @param  array  $where     Assoc array of where values
   * @param  array  $operators Array of operators
   * @return array             Array of $where_clause and $where_values
   */
  public function prep_where($where, $operators) {
    $where_clause = '';
    $where_values = [];
    $count = 0;


    foreach ($where as $field => $value) {
      if ($count > 0) {
        if (!empty($operators[$count - 1])) {
          $where_clause .= ' ' . $operators[$count - 1] . ' ';
        } else {
          $where_clause .= ' AND ';
        }
      }

      $where_clause .= $field . '?';
      $where_values[] = $value;

      $count++;
    }

    return array($where_clause, $where_values);
  }


  /**
   * Escapes HTML characters from each element in an array eg $_POST
   * @param  array  $array The array of strings to escape
   * @return array         Escaped array of strings
   */
  public function htmlchars($array) {
    return array_map('htmlspecialchars', $array);
  }

}
