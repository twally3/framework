<?php

namespace Framework\Core\Foundation\Console;

use Framework\Core\Foundation\Application;
use ReflectionClass;

Class CliKernel {

	/**
	 * The application container
	 * @var Application
	 */
	protected $app;

	/**
	 * The registered commands
	 * @var array
	 */
	protected $commands;


	/**
	 * Binds the dependencies
	 * @param Application $app The application container instance
	 */
	public function __construct(Application $app) {
		$this->app = $app;
		$this->commands = require_once $this->app->basepath . '/App/Config/console.php';

	}


	/**
	 * Run the Cli Kernal
	 * @param  array  $args The arguements passed by the console
	 * @return resource     The result of the method run
	 */
	public function handle($args) {
		$this->cleanArgs($args);

		if (array_key_exists($this->command, $this->commands['commands'])) {
			$reflection = new ReflectionClass($this->commands['commands'][$this->command]);
			$reflection = strtolower($reflection->getShortName());

			$this->app->bind($reflection, $this->commands['commands'][$this->command]);

			$class = $this->app->resolve($reflection);
			$method = $this->method;
			return $class->$method($this->args);
		}

		echo "Command does not exist!\n";
	}

	/**
	 * Removes useless beginning args
	 * @param  array  $args Args from the CLI
	 * @return void
	 */
	public function cleanArgs($args) {
		$command = explode(':', $args[1]);
		$this->command = $command[0];
		$this->method = isset($command[1]) ? $command[1] : 'handle';

		unset($args[0]);
		unset($args[1]);

		$this->args = array_values($args);
	}

}