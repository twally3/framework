<?php

namespace Framework\Core\Console;

use Framework\Core\Support\CommandInterface;
use Framework\Core\Foundation\Application;
use Framework\Core\Database\Migrations;

Class MigrateCommand implements CommandInterface {

	protected $app;
	protected $migrations;

	public function __construct(Application $app, Migrations $migrations) {

		$this->app = $app;
		$this->migrations = $migrations;

	}

	// Migrate
	public function handle($args) {
		$this->migrations->migrate();
	}

	// Migrate:Make
	public function make($args) {
		$this->migrations->make($args[0]);
	}

	//Migrate:rollback
	public function rollback() {
		$this->migrations->rollback();
	}

	//Migrate:install
	public function install() {
		echo "Creating Migrations table \n";
		$this->migrations->install();
	  echo "Table created successfully \n";
	}

	//Migrate:init
	public function init() {
		$this->migrations->init();
	}

}