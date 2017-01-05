<?php

namespace Framework\Core\Console;

use Framework\Core\Support\CommandInterface;
use Framework\Core\Foundation\Application;

Class MakeCommand implements CommandInterface {

	/**
	 * The stored singleton instance of the Application Container
	 * @var Application
	 */
	protected $app;

	/**
	 * Constructor function stores the dependencies to the class
	 * @param Application $app Singleton application instance
	 * @return void
	 */
	public function __construct(Application $app) {
		$this->app = $app;
	}

	/**
	 * Required by Interface but not used
	 * @param  array $args Arguments passed from the CLI Kernal
	 * @return void
	 */
	public function handle($args) {
		
	}

	/**
	 * make:command function creates a command template file
	 * @param  array $args  Arguments passed from the CLI Kernal
	 * @return void
	 */
	public function command($args) {
		if (!isset($args[0])) {
			die("Name not given \n");
	    }

	    $name = ucfirst($args[0]) . "Command";

	    $this->createFile($name, 'command', '/App/Bootstrap/Commands/');
	}

	/**
	 * make:controller creates a controller template
	 * @param  array $args Arguments passed from the CLI Kernal
	 * @return void
	 */
	public function controller($args) {
		if (!isset($args[0])) {
			die("Name not given \n");
	    }

	    $name = ucfirst($args[0]) . "Controller";

	    $this->createFile($name, 'controller', '/App/Controllers/');
	}

	/**
	 * make:model creates a model template
	 * @param  array $args Arguments passed from the CLI Kernal
	 * @return void
	 */
	public function model($args) {
		if (!isset($args[0])) {
			die("Name not given \n");
	    }

	    $name = ucfirst($args[0]);

	    $this->createFile($name, 'model', '/App/Models/');
	}

	/**
	 * make:provider creates a provider template
	 * @param  array $args Arguments passed from the CLI Kernal
	 * @return void
	 */
	public function provider($args) {
		if (!isset($args[0])) {
			die("Name not given \n");
	    }

	    $name = ucfirst($args[0]) . "Provider";

	    $this->createFile($name, 'provider', '/App/Bootstrap/Providers/');
	}

	/**
	 * make:middleware creates a middleware template
	 * @param  array $args Arguments passed from the CLI Kernal
	 * @return void
	 */
	public function middleware($args) {
		if (!isset($args[0])) {
			die("Name not given \n");
	    }

	    $name = ucfirst($args[0]) . "Middleware";

	    $this->createFile($name, 'middleware', '/App/HTTP/middleware/');
	}

	/**
	 * make:facade creates a facade template
	 * @param  array $args Arguments passed from the CLI Kernal
	 * @return void
	 */
	public function facade($args) {
		if (!isset($args[0])) {
			die("Name not given \n");
	    }

	    $name = ucfirst($args[0]) . "Facade";

	    $this->createFile($name, 'facade', '/app/Bootstrap/Facades/');
	}

	/**
	 * Creates a file with a given name in a given location from a template
	 * @param  string $name         The name of the new file
	 * @param  string $templateName The name of the template file
	 * @param  string $save         The location to store the new file
	 * @return void
	 */
	private function createFile($name, $templateName, $save) {
		$fileName = $name . ".php";

	    $txt = file_get_contents($this->app->basepath . "/vendor/max/Framework/src/Core/Templates/{$templateName}.php");
	    $txt = str_replace('INSERTNAMEHERE', $name, $txt);

	    $myfile = fopen($this->app->basepath . $save . $fileName, "w") or die("Unable to open file!");
	    fwrite($myfile, $txt);
	    fclose($myfile);
	}

}