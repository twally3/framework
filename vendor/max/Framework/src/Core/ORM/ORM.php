<?php namespace Framework\Core\ORM;

use \Database;
use Framework\Core\ORM\ORM;
use \PDO as PDO;

Class ORM {

  protected static $_schema = [];

  public static $_where = [];
  protected static $_insert = [];
  protected static $_operators = [];
  protected static $_additional = '';

  protected static $_vars = ['table', 'hidden', 'primaryKey', 'timestamps'];

  /**
   * Allows join methods to be accessed as properties
   * @param  string $arg The name of the method to run
   * @return Mixed      The result of the method
   */
  function __get($arg) {
    return $this->$arg();
  }


  /**
   * Returns a JSON output when class is cast to string
   * @return string The JSON version of the class
   */
  function __toString() {
    return $this->getJson();
  }


  /**
   * Get the current JSON version of the object
   * @return string JSON output
   */
  function getJson() {
    $objs = unserialize(serialize($this));
    $hidden = isset($objs::$hidden) ? $objs::$hidden : [];

    if (is_object(end($objs))) {
      // List of objects
      foreach ($objs as $obj) {
        foreach ($hidden as $hide) {
          if (isset($obj->$hide)) {
            unset($obj->$hide);
          }
        }
      }
    } else {
      // single object
      foreach ($hidden as $hide) {
        echo $hide;
        if (isset($obj->$hide)) {
          unset($obj->$hide);
        }
      }
    }
    // return $objs;
    return json_encode($objs);
  }


  /**
   * Load all MODEL files and cache the schema
   * @param  Application $app The Application container
   * @return void
   */
  static function setupModels($app) {
    foreach (glob($app->basepath . "/App/Models/*.php") as $filename) {
        require_once $filename;
    }
    
    foreach( get_declared_classes() as $class ){
      if(is_subclass_of($class, 'Framework\Core\ORM\ORM')) {
        $table = self::tableName($class);
        $query = Database::select('INFORMATION_SCHEMA.COLUMNS', null, null, ['TABLE_SCHEMA =' => DB_NAME, 'TABLE_NAME = ' => $table]);
        $data = $query->fetchAll(PDO::FETCH_OBJ);

        self::$_schema[$table] = $data;
        $i = 0;

        foreach(self::$_schema[$table] as $model) {
          if ($model->COLUMN_KEY == 'PRI') {
            unset(self::$_schema[$table][$i]);
            self::$_schema[$table] = array_values(self::$_schema[$table]);
          }
          $i++;
        }
      }
    }
  }


  /**
   * Returns the name of the table that should be referenced plur' model name or table property
   * @param  string $name The name of the model class to check
   * @return string       The name of the table
   */
  static function tableName($name = null) {
    $model = is_null($name) ? self::className() : $name;
    $property = 'table';
    return $table = (isset($model::$$property)) ? strtolower($model::$$property) : strtolower($model . 's');
  }


  /**
   * Returns the name of the primary key field either id or primary key property
   * @param  string $name name of the model class
   * @return string       name of the ID field
   */
  static function primaryKey($name = null) {
    $model = is_null($name) ? self::className() : $name;
    $property = 'primaryKey';
    return $table = (isset($model::$$property)) ? $model::$$property : 'id';
  }


  /**
   * Does the model use the timestamps
   * @return boolean Yes or no
   */
  static function timestamps() {
    $model = self::className();
    $property = 'timestamps';
    return $table = (isset($model::$$property)) ? $model::$$property : true;
  }


  /**
   * Gets the currently targeted classname
   * @return string The class name
   */
  static function className() {
    return get_called_class();
  }


  /**
   * Gets a row with a given id / [1,2,3] id's
   * @param  integer $id The value of the primary key to find
   * @return object      The returned rows
   */
  static function find($id) {
    $class = self::className();
    $name = self::tableName();
    $field = self::primaryKey();

    $data = [];

    if (is_array($id)) {
      foreach ($id as $x) {
        $query = Database::select($name, null, null, ["$field = " => $x]);
        $results = $query->fetchAll(PDO::FETCH_CLASS, $class);
        if (isset($results[0])) {
          $data[] = $results[0];
        }
      }
    } else {
      $query = Database::select($name, null, null, ["$field = " => $id]);
      $results = $query->fetchAll(PDO::FETCH_CLASS, $class);
      if (isset($results[0])) {
        $data = $results[0];
      }
    }

    return $data;
  }


  /**
   * Casts the stdclassobj of objects to object of type $this of objects
   * @param  string $class  name of the class instance to convert to
   * @param  Object $object StdClassObject of objects
   * @return Object         The object of objects of type $this
   */
  static function castClass($class, $object) {
    return unserialize(preg_replace('/^O:\d+:"[^"]++"/', 'O:' . strlen($class) . ':"' . $class . '"', serialize($object)));
  }


  /**
   * Returns all rows from a table
   * @return mixed Object of results or null
   */
  static function all() {
    $class = self::className();
    $name = self::tableName();
    $data = Database::select($name);
    $data = $data->fetchAll(PDO::FETCH_CLASS, $class);

    if (isset($data)) {
      return $data = self::castClass($class, (object) $data);
    }
    return null;
    // return $data = isset($data) ? $data : null;
  }


  /**
   * Initiated where statements
   * @param  string $field The field to target
   * @param  string $value The value to find
   * @return $this         Return reference to the static class for chained methods
   */
  static function where($field, $value) {
    $name = self::tableName();
    self::$_where[$field] = $value;
    return new static;
  }


  /**
   * Add custom operators in order [AND, OR, AND] n-1 * o for n * w
   * @param  array $array The array of operators targeted in order
   * @return $this        Return reference to the static class for chained methods
   */
  function operators($array) {
    self::$_operators = $array;
    return new static;
  }


  /**
   * Order the output rows
   * @param  string $field The field to trget
   * @param  string $value ASC or DESC
   * @return $this         Return reference to the static class for chained methods
   * @todo   Add additional data function
   */
  function orderBy($field, $value) {
    self::$_additional .= "ORDER BY {$field} {$value}";
    return new static;
  }


  /**
   * Get the data follwing a where clause
   * @return mixed null or results
   */
  function get() {
    if (empty(self::$_where)) {
      return null;
    }

    $operators = !empty(self::$_operators) ? self::$_operators : null;
    $additional = !empty(self::$_additional) ? self::$_additional : null;

    $class = self::className();

    $name = self::tableName();
    $data = Database::select($name, null, null, self::$_where, $operators, $additional);
    $data = $data->fetchAll(PDO::FETCH_CLASS, $class);

    self::$_where = [];
    self::$_operators = [];
    self::$_additional = '';

    if (count($data) > 0) {
      return self::castClass($class, (object) $data);
    }
    return null;
    // return $data = (count($data) > 0) ? $data : null;
  }


  /**
   * Get the first row from where clause
   * @return mixed Null or row
   */
  function first() {
    $data = self::get();
    $item = reset($data);
    return (isset($item)) ? $item : null;
  }


  /**
   * Get the number of return objects
   * @return mixed Null or Integer number of objects
   */
  function count() {
    $data = count(self::get());
    return $data = isset($data) ? $data : null;
  }


  /**
   * Get the number with the largest value from the database
   * @param  string $field field to target
   * @return Mixed         The largest value of the given field
   */
  function max($field) {
    $data = self::get();
    $max;
    $test = false;
    foreach ($data as $val) {
      if ($test == false) {
        $max = $val;
        $test = true;
      } else if ($val->$field > $max->$field) {
        $max = $val;
      }
    }
    return $max;
  }


  /**
   * Updates data based on where clause
   * @param  array $update Assoc array of where clause ['name' => 'Geoff']
   * @return boolean       Success
   */
  function update($update) {
    $name = self::tableName();
    if (self::timestamps()) {
      $update['updated_at'] = time();
    }
    $update = Database::update($name, $update, self::$_where);
    self::$_where = [];
    self::$_operators = [];
    self::$_additional = '';

    return $update;
  }


  /**
   * Delete the respective where field and return them
   * @return Object The rows that where deleted
   */
  function delete() {
    $class = self::className();
    $name = self::tableName();
    $operators = !empty(self::$_operators) ? self::$_operators : null;
    $query = Database::select($name, null, null, self::$_where, $operators);
    Database::delete($name, self::$_where, $operators);
    $data = $query->fetchAll(PDO::FETCH_CLASS, $class);

    self::$_where = [];
    self::$_operators = [];
    self::$_additional = '';

    return self::castClass($class, (object) $data);
  }


  /**
   * Saves insert fields on new instance of object
   * @return Boolean Success
   */
  function save() {
    $table = self::tableName();
    $key = self::primaryKey();

    if (self::timestamps()) {
      $time = time();
      self::$_insert['created_at'] = $time;
      self::$_insert['updated_at'] = $time;
    }

    foreach (self::$_schema[$table] as $schema) {
      $column = $schema->COLUMN_NAME;
      if (isset($this->$column)) {
        $regx = '#(?:.*?)\((.*?)\)#';
        preg_match($regx, $schema->COLUMN_TYPE, $matches);

        if (strlen($this->$column) <= $matches[1]) {
          self::$_insert[$column] = $this->$column;
        } else {
          self::$_insert = [];
          throw new \Exception("The data entered of length is over the length {$matches[1]} for {$column}");
          return false;
        }
      } else if ($schema->IS_NULLABLE != 'YES' && $column != 'created_at' && $column != 'updated_at' ) {
        self::$_insert = [];
        throw new \Exception("Missing column value for {$column}");
        return false;
      }
    }


    if (!Database::insert($table, self::$_insert)) {
      throw new \Exception("Insert Failed");
      return false;
    }

    $this->$key = \Database::last_id();

    self::$_insert = [];
    return true;
  }

// WARNING RELATIONAL STUFF AHEAD //


  /**
   * data where other table had a field relating to $this id
   * @param  string $className Model to target
   * @param  string $join      Name of the forign key to joint
   * @return Object            Results
   */
  function hasMany($className, $join=null) {
    $calledClass = self::className();
    $table = self::tableName($className);
    $field = is_null($join) ? $calledClass . "_id" : $join;
    $key = self::primaryKey();
    $value = $this->$key;

    $where = [$field . "=" => $value];

    $query = Database::select($table, null, null, $where);
    $data = $query->fetchAll(PDO::FETCH_CLASS, $className);

    return self::castClass($className, (object) $data);
  }


  /**
   * One to one relationship where forign id joins $this primary key
   * @param  string  $className Target model
   * @param  string  $join      Forign id name
   * @return Object             Results
   */
  function hasOne($className, $join=null) {
    $data = $this->hasMany($className, $join);
    $data = reset($data);
    return $data;
  }


  /**
   * Many $this to one forgin relational id on $this
   * @param  string $className Forign class name
   * @param  string $join      id of this join field
   * @return mixed             Null or results
   */
  function belongsTo($className, $join=null) {
    $table = self::tableName($className);
    $field = self::primaryKey($className);
    $join = is_null($join) ? $className . "_id" : $join;
    $value = $this->$join;

    $where = [$field . "=" => $value];

    $query = Database::select($table, null, null, $where);
    $data = $query->fetchAll(PDO::FETCH_CLASS, $className);

    return isset($data[0]) ? $data[0] : null;
  }


  /**
   * Many to many relationships
   * @param  string $className  Forign model name
   * @param  string $pivotName  Name of pivot defaults to alph order
   * @param  string $pivotThis  Field on pivot relating to this
   * @param  string $pivotOther Field on pivot relating to forign
   * @return object             Results
   */
  function belongsToMany($className, $pivotName = null, $pivotThis = null, $pivotOther = null) {
    $thisClass = self::className();
    $otherClass = $className;

    $otherTable = self::tableName($className);

    $thisField = self::primaryKey();
    $otherField = self::primaryKey($className);

    $thisValue = $this->$thisField;

    list($one, $two) = self::sort($thisClass, $otherClass);

    $pivotName = is_null($pivotName) ? strtolower("{$one}_{$two}") : strtolower($pivotName);
    $pivotThis = is_null($pivotThis) ? $thisClass . "_id" : $pivotThis;
    $pivotOther = is_null($pivotOther) ? "{$otherClass}_id" : $pivotOther;

    $where = ["Y.{$pivotThis} = " => "{$thisValue}"];
    $join = " AS Y JOIN {$otherTable} AS X ON Y.{$pivotOther} = X.{$otherField} ";

    // $sql = "SELECT X.* FROM {$pivotName} AS Y JOIN {$otherTable} AS X ON Y.{$pivotOther} = X.{$otherField} WHERE Y.{$pivotThis} = {$thisValue}";

    $query = Database::select($pivotName, 'X.*', $join, $where);
    $data = $query->fetchAll(PDO::FETCH_CLASS, $className);

    $data = self::castClass($className, (object) $data);

    return $data;
  }


  /**
   * Sorts the classes into alphabetical order
   * @param  string $one First field name
   * @param  string $two Second field name
   * @return array       Sorted list
   * @todo   actually make this work
   */
  static function sort($one, $two) {
    $x = [$one, $two];
    rsort($x);
    return $x;
  }
}
