<?php

class Migrations {

  static function install() {
    echo "Creating Migrations table \n";

    $sql = "CREATE TABLE migrations
            (
              migration VARCHAR(255) NOT NULL UNIQUE,
              batch INT NOT NULL
            );";

    $query = Database::query($sql);
    echo "Table created successfully \n";
  }

  static function make($name) {
    if (!isset($name)) {
      die("Name not given \n");
    }

    $name = $name;
    $fileName = time() . "_" . $name . ".php";

    $txt = file_get_contents("./core/template.php");
    $txt = str_replace('INSERTNAMEHERE', $name, $txt);

    $myfile = fopen('./migrations/'.$fileName, "w") or die("Unable to open file!");
    fwrite($myfile, $txt);
    fclose($myfile);
  }

  static function migrate() {
    $files = [];
    $batch = 0;
    $migrations = [];

    $query = Database::select('migrations');
    $data = $query->fetchAll(PDO::FETCH_OBJ);

    foreach ($data as $x) {
      if ($x->batch > $batch) {
        $batch = $x->batch;
      }
      $migrations[] = $x->migration;
    }

    foreach (glob("./migrations/*.php") as $filename) {
        $file = explode("/", $filename);
        $file = array_pop($file);
        $file = explode(".", $file);
        $file = $file[0];

        if (!in_array($file, $migrations)) {
          $files[] = $file;
        }
    }

    foreach ($files as $file) {
      self::runUp($file);
      Database::insert('migrations', ['migration' => $file, 'batch' => $batch + 1]);
    }
  }

  static function rollback() {
    $batch = 0;
    $names = [];

    $query = Database::select('migrations');
    $data = $query->fetchAll(PDO::FETCH_OBJ);

    foreach ($data as $x) {
      if ($x->batch > $batch) {
        $batch = $x->batch;
      }
    }


    $query = Database::select('migrations', null, null, ['batch =' => $batch]);
    $data = $query->fetchAll(PDO::FETCH_OBJ);
    $data = array_reverse($data);

    foreach ($data as $x) {
      self::runDown($x->migration);
    }

    Database::delete('migrations', ['batch =' => $batch]);
  }

  static function runUp($file) {
    $dir = "./migrations/" . $file . ".php";

    include $dir;

    $class = explode('_', $file);
    array_shift($class);
    $class = implode('_', $class);

    $x = new $class;
    $x->up();
  }

  static function runDown($file) {
    $dir = "./migrations/" . $file . ".php";

    include $dir;

    $class = explode('_', $file);
    array_shift($class);
    $class = implode('_', $class);

    $x = new $class;
    $x->down();
  }

  static function seed($name) {
    echo "[NOTE] Seeding table $name \n";
    if (!isset($name)) {
      die("Name not given \n");
    }

    $name = $name;
    $fileName = $name . ".json";

    $query = Database::select($name);
    $data = $query->fetchAll(PDO::FETCH_OBJ);
    $data = json_encode($data);

    if (file_exists('./seeds/'.$fileName)) {
      unlink('./seeds/'.$fileName);
      echo "Deleted old {$fileName} \n";
    }

    $myfile = fopen('./seeds/'.$fileName, "w") or die("Unable to open file!");
    fwrite($myfile, $data);
    fclose($myfile);

    echo "[SUCCESS] Seeded table $name \n";
  }

  static function upload($name) {
    echo "[NOTE] Uploading data from table $name \n";
    $columns = [];
    $string = '';
    $cols = '';
    $vals = '';

    if (!isset($name)) {
      die("Name not given \n");
    }

    $name = $name;
    $fileName = $name . ".json";

    $query = Database::select('INFORMATION_SCHEMA.COLUMNS', null, null, ['TABLE_SCHEMA =' => DB_NAME, 'TABLE_NAME = ' => $name]);
    $data = $query->fetchAll(PDO::FETCH_OBJ);

    foreach ($data as $x) {
      $columns[] = $x->COLUMN_NAME;
      $cols .= $x->COLUMN_NAME . ", ";
    }

    $cols = rtrim($cols, ', ');
    $cols = "({$cols})";

    $contents = file_get_contents('./seeds/'.$fileName);
    $contents = json_decode($contents);

    foreach ($contents as $content) {
      $vals .= '(';
      foreach ($columns as $column) {
        $vals .= "'" . $content->$column . "',";
      }
      $vals = rtrim($vals, ',');
      $vals .= '),';
    }
    $vals = rtrim($vals, ',');

    echo "[NOTE] Truncating table $name \n";

    Database::query("TRUNCATE TABLE {$name}");

    $sql = "INSERT INTO {$name} {$cols} VALUES {$vals}";
    echo "[NOTE] Applying SQL $sql \n";
    Database::query($sql);

    echo "[SUCCESS] Successfully restored data for $name \n";

  }

  public function init() {
    echo "[NOTE] Initialising project \n";
    self::migrate();

    $seeds = glob("./seeds/*.json");

    foreach ($seeds as $seed) {
      $file = explode("/", $seed);
      $file = array_pop($file);
      $file = explode(".", $file);
      $file = $file[0];

      self::upload($file);
    }

    echo "[SUCCESS] Database initiation completed \n";

  }
}
