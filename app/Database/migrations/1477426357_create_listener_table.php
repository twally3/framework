<?php

namespace App\Database\Migrations;

use Framework\Core\Support\MigrationsInterface;
use Framework\Core\Database\Migrations;
use \Schema;

class create_listener_table implements MigrationsInterface {

  public function up() {

    Schema::create('listeners', function($t) {
      $t->int('id')->notNull()->incriment()->primary();
      $t->varchar('name', 128)->notNull();
    });

  }

  public function down() {

    Schema::drop('listeners');

  }

}
