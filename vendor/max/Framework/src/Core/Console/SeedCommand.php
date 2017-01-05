<?php

namespace Framework\Core\Console;

use Framework\Core\Support\CommandInterface;
use Framework\Core\Foundation\Application;
use Framework\Core\Database\Migrations;

Class SeedCommand implements CommandInterface {

	/**
	 * Application Container
	 * @var Application
	 */
	protected $app;

	/**
	 * Migrations instance
	 * @var Migrations
	 */
	protected $migrations;


	/**
	 * Bind dependencies to the container
	 * @param Application $app        Singleton instance of the application container
	 * @param Migrations  $migrations Instance of the Migrations class
	 */
	public function __construct(Application $app, Migrations $migrations) {

		$this->app = $app;
		$this->migrations = $migrations;

	}

	
	/**
	 * seed command, seeds a specified table
	 * @param  array  $args Arguements passed from Cli Kernal
	 * @return void
	 */
	public function handle($args) {
		$this->migrations->seed($args[0]);
	}

	
	/**
	 * seed:upload command, uploads a seed for a specified table
	 * @param  array  $args Arguements passed from Cli Kernal
	 * @return void
	 */
	public function upload($args) {
		$this->migrations->upload($args[0]);
	}

}