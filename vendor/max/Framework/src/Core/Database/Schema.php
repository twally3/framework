<?php

namespace Framework\Core\Database;

use Framework\Core\Foundation\Application;
use Framework\Core\Database\Table;

class Schema {

  /**
   * Binds the dependencies to the class
   * @param Application $app The Application container
   * @param Database    $db  The database instance
   */
  public function __construct(Application $app, Database $db) {
    $this->db = $db;
    $this->app = $app;
  }


  /**
   * Creates a new table
   * @param  string  $name     Name of the table to create
   * @param  closure $callback Closure function will be passed the table instance
   * @return void
   */
  function create($name, $callback) {
    $table = $this->app->resolve('table');

    call_user_func($callback, $table);

    $query = $table->create();
    $query = "CREATE TABLE `$name` (" . $query . ")";

    echo "[NOTE] Trying: $query ...\n";
    $this->db->query($query);

    echo "[SUCCESS] Added table [$name] \n";
  }


  /**
   * Drop the specified table
   * @param  string $name The name of the table to drop
   * @return void
   */
  function drop($name) {
    $query = "DROP TABLE {$name}";
    echo "[NOTE] Trying: $query ...\n";
    $this->db->query($query);
    echo "[SUCCESS] Dropped table [$name] \n";
  }


  /**
   * Update fields in an existing table
   * @param  string  $name     The name of the target table
   * @param  closure $callback Closure function will be passed the table class
   * @return void
   */
  function table($name, $callback) {
    $table = $this->app->resolve('table');

    call_user_func($callback, $table);

    $query = $table->create();
    $query = rtrim($query, ',');
    $query = "ALTER TABLE `$name` ADD (" . $query . ")";

    echo "[NOTE] Trying: $query ...\n";
    $this->db->query($query);

    echo "[SUCCESS] Updated table [$name] \n";
  }


  /**
   * Deletes a given column in the target table
   * @param  string $table  The name of the target table
   * @param  string $column The name of the target column
   * @return void
   */
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
