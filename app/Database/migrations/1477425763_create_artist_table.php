<?php

use Framework\Core\Database\Migrations as Migrations;
use Framework\Core\Database\Schema as Schema;


class create_artist_table extends Migrations {

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
