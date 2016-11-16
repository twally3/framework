<?php namespace Framework\Core\Database;

class Schema {

  static function create($name, $callback) {
    $table = new Table;

    call_user_func($callback, $table);

    $query = $table->create();
    $query = "CREATE TABLE `$name` (" . $query . ")";

    echo "[NOTE] Trying: $query ...\n";
    Database::query($query);

    echo "[SUCCESS] Added table [$name] \n";
  }

  static function drop($name) {
    $query = "DROP TABLE {$name}";
    echo "[NOTE] Trying: $query ...\n";
    Database::query($query);
    echo "[SUCCESS] Dropped table [$name] \n";
  }

  static function table($name, $callback) {
    $table = new Table;

    call_user_func($callback, $table);

    $query = $table->create();
    $query = "ALTER TABLE `$name` ADD" . $query;
    $query = rtrim($query, ',');

    echo "[NOTE] Trying: $query ...\n";
    Database::query($query);

    echo "[SUCCESS] Updated table [$name] \n";
  }


  static function dropColumn($table, $column) {
    $test = Database::query("SHOW TABLES LIKE '$table'");
    if ($test->rowCount() > 0) {
      $query = "ALTER TABLE $table DROP $column IF EXISTS";
      echo "[NOTE] Trying: $query ...\n";
      Database::query($query);
      echo "[SUCCESS] Dropped $column from [$table] \n";
    }
  }
}
