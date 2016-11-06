<?php

class create_listener_table extends Migrations {

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
