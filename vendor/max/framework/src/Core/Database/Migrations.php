<?php 

namespace Framework\Core\Database;

use \PDO as PDO;
use Framework\Core\Foundation\Application;

class Migrations {

  public function __construct(Application $app, Database $database) {
    $this->app = $app;
    $this->db = $database;

    require_once $this->app->basepath . '/vendor/max/framework/src/core/support/MigrationsInterface.php';
  }

  function install() {
    $sql = "CREATE TABLE migrations
            (
              migration VARCHAR(255) NOT NULL UNIQUE,
              batch INT NOT NULL
            );";

    $query = $this->db->query($sql);
  }

  function make($name) {
    if (!isset($name)) {
      die("Name not given \n");
    }

    $name = $name;
    $fileName = time() . "_" . $name . ".php";

    $txt = file_get_contents($this->app->basepath . "/vendor/max/framework/src/core/Database/template.php");
    $txt = str_replace('INSERTNAMEHERE', $name, $txt);

    $myfile = fopen($this->app->basepath . '/app/Database/migrations/'.$fileName, "w") or die("Unable to open file!");
    fwrite($myfile, $txt);
    fclose($myfile);
  }

  function migrate() {
    $files = [];
    $batch = 0;
    $migrations = [];

    $query = $this->db->select('migrations');
    $data = $query->fetchAll(PDO::FETCH_OBJ);

    foreach ($data as $x) {
      if ($x->batch > $batch) {
        $batch = $x->batch;
      }
      $migrations[] = $x->migration;
    }

    foreach (glob($this->app->basepath . "/app/database/migrations/*.php") as $filename) {
        $file = explode("/", $filename);
        $file = array_pop($file);
        $file = explode(".", $file);
        $file = $file[0];

        if (!in_array($file, $migrations)) {
          $files[] = $file;
        }
    }

    foreach ($files as $file) {
      $this->runUp($file);
      $this->db->insert('migrations', ['migration' => $file, 'batch' => $batch + 1]);
    }
  }

  function rollback() {
    $batch = 0;
    $names = [];

    $query = $this->db->select('migrations');
    $data = $query->fetchAll(PDO::FETCH_OBJ);

    foreach ($data as $x) {
      if ($x->batch > $batch) {
        $batch = $x->batch;
      }
    }


    $query = $this->db->select('migrations', null, null, ['batch =' => $batch]);
    $data = $query->fetchAll(PDO::FETCH_OBJ);
    $data = array_reverse($data);

    foreach ($data as $x) {
      $this->runDown($x->migration);
    }

    $this->db->delete('migrations', ['batch =' => $batch]);
  }

  function runUp($file) {
    $dir = $this->app->basepath . "/app/Database/migrations/" . $file . ".php";

    include $dir;

    $class = explode('_', $file);
    array_shift($class);
    $class = implode('_', $class);

    $class = 'App\Database\Migrations\\' . $class;
    $x = new $class;
    $x->up();
  }

  function runDown($file) {
    $dir = $this->app->basepath . "/app/Database/migrations/" . $file . ".php";

    include $dir;

    $class = explode('_', $file);
    array_shift($class);
    $class = implode('_', $class);
    
    $class = 'App\Database\Migrations\\' . $class;
    $x = new $class;
    $x->down();
  }

  function seed($name) {
    echo "[NOTE] Seeding table $name \n";
    if (!isset($name)) {
      die("Name not given \n");
    }

    $name = $name;
    $fileName = $name . ".json";

    $query = $this->db->select($name);
    $data = $query->fetchAll(PDO::FETCH_OBJ);
    $data = json_encode($data);

    if (file_exists($this->app->basepath . '/app/database/seeds/'.$fileName)) {
      unlink($this->app->basepath . '/app/database/seeds/'.$fileName);
      echo "Deleted old {$fileName} \n";
    }

    $myfile = fopen($this->app->basepath . '/app/database/seeds/'.$fileName, "w") or die("Unable to open file!");
    fwrite($myfile, $data);
    fclose($myfile);

    echo "[SUCCESS] Seeded table $name \n";
  }

  function upload($name) {
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

    $query = $this->db->select('INFORMATION_SCHEMA.COLUMNS', null, null, ['TABLE_SCHEMA =' => DB_NAME, 'TABLE_NAME = ' => $name]);
    $data = $query->fetchAll(PDO::FETCH_OBJ);

    foreach ($data as $x) {
      $columns[] = $x->COLUMN_NAME;
      $cols .= $x->COLUMN_NAME . ", ";
    }

    $cols = rtrim($cols, ', ');
    $cols = "({$cols})";

    $contents = file_get_contents($this->app->basepath . '/app/database/seeds/'.$fileName);
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

    $this->db->query("TRUNCATE TABLE {$name}");

    $sql = "INSERT INTO {$name} {$cols} VALUES {$vals}";
    echo "[NOTE] Applying SQL $sql \n";
    $this->db->query($sql);

    echo "[SUCCESS] Successfully restored data for $name \n";

  }

  public function init() {
    echo "[NOTE] Initialising project \n";
    $this->migrate();

    $seeds = glob($this->app->basepath . "/app/database/seeds/*.json");

    foreach ($seeds as $seed) {
      $file = explode("/", $seed);
      $file = array_pop($file);
      $file = explode(".", $file);
      $file = $file[0];

      $this->upload($file);
    }

    echo "[SUCCESS] Database initiation completed \n";

  }
}
