<?php

namespace App\Database\Migrations;

use Framework\Core\Support\MigrationsInterface;
use Framework\Core\Database\Migrations;
use \Schema;

class create_artist_table implements MigrationsInterface {

  public function up() {

    Schema::create('artists', function($t) {
      $t->int('id')->incriment()->notNull()->primary();
      $t->varchar('name', 128)->notNull();
    });

  }

  public function down() {

    Schema::drop('artists');

  }

}
