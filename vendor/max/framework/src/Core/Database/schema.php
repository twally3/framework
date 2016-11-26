<?php

namespace Framework\Core\Database;

use Framework\Core\Foundation\Application;
use Framework\Core\Database\Table;

class Schema {

  public function __construct(Application $app, Database $db) {
    $this->db = $db;
    $this->app = $app;
  }

  function create($name, $callback) {
    $table = $this->app->resolve('table');

    call_user_func($callback, $table);

    $query = $table->create();
    $query = "CREATE TABLE `$name` (" . $query . ")";

    echo "[NOTE] Trying: $query ...\n";
    $this->db->query($query);

    echo "[SUCCESS] Added table [$name] \n";
  }

  function drop($name) {
    $query = "DROP TABLE {$name}";
    echo "[NOTE] Trying: $query ...\n";
    $this->db->query($query);
    echo "[SUCCESS] Dropped table [$name] \n";
  }

  function table($name, $callback) {
    $table = $this->app->resolve('table');

    call_user_func($callback, $table);

    $query = $table->create();
    $query = "ALTER TABLE `$name` ADD" . $query;
    $query = rtrim($query, ',');

    echo "[NOTE] Trying: $query ...\n";
    $this->db->query($query);

    echo "[SUCCESS] Updated table [$name] \n";
  }


  function dropColumn($table, $column) {
    $test = $this->db->query("SHOW TABLES LIKE '$table'");
    if ($test->rowCount() > 0) {
      $query = "ALTER TABLE $table DROP $column";
      echo "[NOTE] Trying: $query ...\n";
      $this->db->query($query);
      echo "[SUCCESS] Dropped $column from [$table] \n";
    }
  }
}
