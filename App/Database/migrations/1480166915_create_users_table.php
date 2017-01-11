<?php

namespace App\Database\Migrations;

use Framework\Core\Support\MigrationsInterface;
use Framework\Core\Database\Migrations;
use \Schema;

class create_users_table implements MigrationsInterface {

  public function up() {

    Schema::create('users', function($t) {
      $t->int('id')->incriment()->notNull()->primary();

      $t->varchar('name', 128);
      $t->varchar('email', 128);
      $t->varchar('password', 256);

      $t->timestamps();
    });

  }

  public function down() {

    Schema::drop('users');

  }

}
