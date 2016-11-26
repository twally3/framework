<?php

namespace App\Database\Migrations;

use Framework\Core\Support\MigrationsInterface;
use Framework\Core\Database\Migrations;
use \Schema;

class create_album_listener_table implements MigrationsInterface {

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
