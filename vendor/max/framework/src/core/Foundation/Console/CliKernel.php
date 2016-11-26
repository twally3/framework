<?php

namespace Framework\Core\Foundation\Console;

use Framework\Core\Foundation\Application;
use ReflectionClass;

Class CliKernel {

	protected $app;
	protected $commands;

	public function __construct(Application $app) {
		$this->app = $app;
		$this->commands = require_once $this->app->basepath . '/app/config/console.php';

	}

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

	// public function includeCommands() {
	// 	foreach ($this->commands['commands'] as $key => $value) {
	// 		require_once $this->app->basepath . $value['path'] . '.php';
	// 	}
	// }

	public function cleanArgs($args) {
		$command = explode(':', $args[1]);
		$this->command = $command[0];
		$this->method = isset($command[1]) ? $command[1] : 'handle';

		unset($args[0]);
		unset($args[1]);

		$this->args = array_values($args);
	}

}