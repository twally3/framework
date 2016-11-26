<?php

namespace App\Database\Migrations;

use Framework\Core\Support\MigrationsInterface;
use Framework\Core\Database\Migrations;
use \Schema;

class create_album_table implements MigrationsInterface {

  public function up() {

    Schema::create('albums', function($t) {
      $t->int('id')->notNull()->incriment()->primary();
      $t->varchar('name', 128)->notNull();
      $t->int('artist_id')->notNull();
    });

  }

  public function down() {

    Schema::drop('albums');

  }

}
