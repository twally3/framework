<?php

Class ORM {

  protected static $_schema = [];

  protected static $_where = [];
  protected static $_insert = [];
  protected static $_operators = [];
  protected static $_additional = '';

  function __get($arg) {
    return $this->$arg();
  }

  static function setupModels() {
    foreach (glob("../app/models/*.php") as $filename) {
        require_once $filename;
    }
    
    foreach( get_declared_classes() as $class ){
      if(is_subclass_of($class, 'ORM')) {
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

  static function tableName($name = null) {
    $model = is_null($name) ? self::className() : $name;
    $property = 'table';
    return $table = (isset($model::$$property)) ? $model::$$property : $model . 's';
  }

  static function primaryKey($name = null) {
    $model = is_null($name) ? self::className() : $name;
    $property = 'primaryKey';
    return $table = (isset($model::$$property)) ? $model::$$property : 'id';
  }

  static function timestamps() {
    $model = self::className();
    $property = 'timestamps';
    return $table = (isset($model::$$property)) ? $model::$$property : true;
  }

  static function className() {
    return get_called_class();
  }

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

  static function all() {
    $class = self::className();
    $name = self::tableName();
    $data = Database::select($name);
    $data = $data->fetchAll(PDO::FETCH_CLASS, $class);

    return $data = isset($data) ? $data : null;
  }

  static function where($field, $value) {
    $name = self::tableName();
    self::$_where[$field] = $value;
    return new static;
  }

  function operators($array) {
    self::$_operators = $array;
    return new static;
  }

  function orderBy($field, $value) {
    self::$_additional .= "ORDER BY {$field} {$value}";
    return new static;
  }

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

    return $data = count($data) > 0 ? $data : null;
  }

  function first() {
    $data = self::get();
    return $data = isset($data[0]) ? $data[0] : null;
  }

  function count() {
    $data = count(self::get());
    return $data = isset($data) ? $data : null;
  }

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

  function update($update) {
    $name = self::tableName();
    if (self::timestamps()) {
      $update['updated_at'] = time();
    }
    return Database::update($name, $update, self::$_where);
  }

  function delete() {
    $class = self::className();
    $name = self::tableName();
    $operators = !empty(self::$_operators) ? self::$_operators : null;
    $query = Database::select($name, null, null, self::$_where, $operators);
    Database::delete($name, self::$_where, $operators);
    return $query->fetchAll(PDO::FETCH_CLASS, $class);
  }

  function save() {
    $table = self::tableName();
    $key = self::primaryKey();

    if (self::timestamps()) {
      echo 'yes';
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
          throw new Exception("The data entered of length is over the length {$matches[1]} for {$column}");
          return false;
        }
      } else if ($schema->IS_NULLABLE != 'YES' && $column != 'created_at' && $column != 'updated_at' ) {
        self::$_insert = [];
        throw new Exception("Missing column value for {$column}");
        return false;
      }
    }


    if (!Database::insert($table, self::$_insert)) {
      throw new Exception("Insert Failed");
      return false;
    }

    $this->$key = Database::last_id();

    self::$_insert = [];
    return true;
  }

// WARNING RELATIONAL STUFF AHEAD //

  function hasMany($className, $join=null) {
    $calledClass = self::className();
    $table = self::tableName($className);
    $field = is_null($join) ? $calledClass . "_id" : $join;
    $key = self::primaryKey();
    $value = $this->$key;

    $where = [$field . "=" => $value];

    $query = Database::select($table, null, null, $where);
    return $data = $query->fetchAll(PDO::FETCH_CLASS, $className);
  }

  function hasOne($className, $join=null) {
    $data = $this->hasMany($className, $join);
    return $data[0];
  }

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

  function belongsToMany($className, $pivotName = null, $pivotThis = null, $pivotOther = null) {
    $thisClass = self::className();
    $otherClass = $className;

    $otherTable = self::tableName($className);

    $thisField = self::primaryKey();
    $otherField = self::primaryKey($className);

    $thisValue = $this->$thisField;

    list($one, $two) = self::sort($thisClass, $otherClass);
    $pivotName = is_null($pivotName) ? "{$one}_{$two}" : $pivotName;
    $pivotThis = is_null($pivotThis) ? $thisClass . "_id" : $pivotThis;
    $pivotOther = is_null($pivotOther) ? "{$otherClass}_id" : $pivotOther;

    $where = ["Y.{$pivotThis} = " => "{$thisValue}"];
    $join = " AS Y JOIN {$otherTable} AS X ON Y.{$pivotOther} = X.{$otherField} ";

    // $sql = "SELECT X.* FROM {$pivotName} AS Y JOIN {$otherTable} AS X ON Y.{$pivotOther} = X.{$otherField} WHERE Y.{$pivotThis} = {$thisValue}";

    $query = Database::select($pivotName, 'X.*', $join, $where);
    $data = $query->fetchAll(PDO::FETCH_CLASS, $className);

    return $data;
  }

  static function sort($one, $two) {
    $x = [$one, $two];
    sort($x);
    return $x;
  }
}
