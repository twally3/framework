<?php

namespace Framework\Core\Support;

interface MigrationsInterface {
	public function up();
	public function down();
}