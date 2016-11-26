<?php

namespace Framework\Core\Console;

use Framework\Core\Support\CommandInterface;
use Framework\Core\Foundation\Application;
use Framework\Core\Database\Migrations;

Class SeedCommand implements CommandInterface {

	protected $app;
	protected $migrations;

	public function __construct(Application $app, Migrations $migrations) {

		$this->app = $app;
		$this->migrations = $migrations;

	}

	// seed
	public function handle($args) {
		$this->migrations->seed($args[0]);
	}

	// Seed:Upload
	public function upload($args) {
		$this->migrations->upload($args[0]);
	}

}