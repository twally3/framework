<?php

use Framework\Core\Database\Migrations as Migrations;
use Framework\Core\Database\Schema as Schema;

class create_album_table extends Migrations {

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
