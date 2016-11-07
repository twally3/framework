<?php

class Database {
  public static $_connection;

  public static function construct() {
    try {
      $handler = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
      $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      printf ("Connect failed %s\n", $e->getMessage());
      exit();
    }

    self::$_connection = $handler;
  }

  public static function insert($table, $data) {
    list($fields, $placeholders, $values) = self::prep_values($data);

    $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
    $query = self::$_connection->prepare($sql);

    return $query->execute($values) ? true : false;
  }

  public static function update($table, $data, $where, $operators=array()) {
    list($fields, $placeholders, $values) = self::prep_values($data, 'update');
    list($where_clause, $where_values) = self::prep_where($where, $operators);

    $values = array_merge($values, $where_values);

    $sql = "UPDATE {$table} SET {$placeholders} WHERE {$where_clause}";

    $query = self::$_connection->prepare($sql);

    return $query->execute($values) ? true : false;
  }

  public static function delete($table, $where, $operators=array()) {
    list($where_clause, $where_values) = self::prep_where($where, $operators);

    $sql = "DELETE FROM {$table} WHERE {$where_clause}";
    $query = self::$_connection->prepare($sql);

    return $query->execute($where_values) ? true : false;
  }

  public static function select($table, $headings=null, $join=null, $where=array(), $operators=array(), $additional='') {
    $headings = is_null($headings) ? '*' : $headings;
    $where = is_null($where) ? array() : $where;
    $operators = is_null($operators) ? array() : $operators;
    $join = is_null($join) ? '' : $join;

    list($where_clause, $where_values) = self::prep_where($where, $operators);
    $where_values = !empty($where_values) ? $where_values : array();

    $sql = "SELECT {$headings} FROM {$table}";

    if (!empty($join)) {
      $sql .= " " . $join . " ";
    }

    if (!empty($where)) {
      $sql .= " WHERE {$where_clause}";
    }

    $sql .= " " . $additional;

    $query = self::$_connection->prepare($sql);
    $query->execute($where_values);

    return $query;
  }

  public static function query($sql) {
    return self::$_connection->query($sql);
  }

  public static function quote($string) {
    return self::$_connection->quote($string);
  }

  public static function last_id() {
    return self::$_connection->lastInsertId();
  }

  public static function prep_values($data, $type='insert') {
    $fields = '';
    $placeholders = '';
    $values = array();

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

    $values = self::htmlchars($values);

    return array($fields, $placeholders, $values);
  }

  public static function prep_where($where, $operators) {
    $where_clause = '';
    $where_values = '';
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

  public static function htmlchars($array) {
    return array_map('htmlspecialchars', $array);
  }

  public static function hash_password($password, $nonce) {
    $secureHash = hash_hmac('sha512', $password . $nonce, SITE_KEY);

    return $secureHash;
  }
}
