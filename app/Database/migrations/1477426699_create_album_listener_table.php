<?php

use Framework\Core\Database\Migrations as Migrations;
use Framework\Core\Database\Schema as Schema;

class create_album_listener_table extends Migrations {

  public function up() {

    Schema::create('album_listener', function($t) {
      $t->int('album_id')->notNull();
      $t->int('listener_id')->notNull();
    });

  }

  public function down() {

    Schema::drop('album_listener');

  }

}
