<?php

namespace App\Database\Migrations;

use Framework\Core\Support\MigrationsInterface;
use Framework\Core\Database\Migrations;
use \Schema;

class add_age_to_artists implements MigrationsInterface {

  public function up() {

    Schema::table('artists', function($t) {
    	$t->int('age');
    });

  }

  public function down() {

    Schema::dropColumn('artists', 'age');

  }

}
