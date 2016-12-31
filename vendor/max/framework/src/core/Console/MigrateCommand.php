<?php

namespace Framework\Core\Console;

use Framework\Core\Support\CommandInterface;
use Framework\Core\Foundation\Application;
use Framework\Core\Database\Migrations;

Class MigrateCommand implements CommandInterface {

	/**
	 * The Application container
	 * @var Application
	 */
	protected $app;


	/**
	 * The Migration Instance
	 * @var Migrations
	 */
	protected $migrations;


	/**
	 * Bind the dependencies to the class
	 * @param Application $app        Instance of the application container
	 * @param Migrations  $migrations Instance of the migration class
	 */
	public function __construct(Application $app, Migrations $migrations) {

		$this->app = $app;
		$this->migrations = $migrations;

	}


	/**
	 * migrate command, runs the migration
	 * @param  array  $args Arguements passed from Cli Kernal
	 * @return void
	 */
	public function handle($args) {
		$this->migrations->migrate();
	}


	/**
	 * migrate:make command, makes a migration file
	 * @param  array  $args Arguements passed from Cli Kernal
	 * @return void
	 */
	public function make($args) {
		$this->migrations->make($args[0]);
	}

	
	/**
	 * migrate:rollback command, rolls back the previous migration
	 * @param  array  $args Arguements passed from Cli Kernal
	 * @return void
	 */
	public function rollback() {
		$this->migrations->rollback();
	}

	
	/**
	 * migrate:install command, creates the migration table
	 * @param  array  $args Arguements passed from Cli Kernal
	 * @return void
	 */
	public function install() {
		echo "Creating Migrations table \n";
		$this->migrations->install();
	  echo "Table created successfully \n";
	}

	
	/**
	 * migrate:init command, runs all migrations and seeds
	 * @param  array  $args Arguements passed from Cli Kernal
	 * @return void
	 */
	public function init() {
		$this->migrations->init();
	}

}