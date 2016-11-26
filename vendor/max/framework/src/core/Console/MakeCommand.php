<?php

namespace Framework\Core\Console;

use Framework\Core\Support\CommandInterface;
use Framework\Core\Foundation\Application;

Class MakeCommand implements CommandInterface {

	protected $app;

	public function __construct(Application $app) {

		$this->app = $app;

	}

	public function handle($args) {
		
	}

	public function command($args) {
		if (!isset($args[0])) {
			die("Name not given \n");
	    }

	    $name = ucfirst($args[0]) . "Command";

	    $this->createFile($name, 'command', '/app/Bootstrap/Commands/');
	}

	public function controller($args) {
		if (!isset($args[0])) {
			die("Name not given \n");
	    }

	    $name = ucfirst($args[0]) . "Controller";

	    $this->createFile($name, 'controller', '/app/Controllers/');
	}

	public function model($args) {
		if (!isset($args[0])) {
			die("Name not given \n");
	    }

	    $name = ucfirst($args[0]);

	    $this->createFile($name, 'model', '/app/Models/');
	}

	public function provider($args) {
		if (!isset($args[0])) {
			die("Name not given \n");
	    }

	    $name = ucfirst($args[0]) . "Provider";

	    $this->createFile($name, 'provider', '/app/Bootstrap/Providers/');
	}

	public function middleware($args) {
		if (!isset($args[0])) {
			die("Name not given \n");
	    }

	    $name = ucfirst($args[0]) . "Middleware";

	    $this->createFile($name, 'middleware', '/app/http/middleware/');
	}

	public function facade($args) {
		if (!isset($args[0])) {
			die("Name not given \n");
	    }

	    $name = ucfirst($args[0]) . "Facade";

	    $this->createFile($name, 'facade', '/app/Bootstrap/Facades/');
	}

	private function createFile($name, $templateName, $save) {
		$fileName = $name . ".php";

	    $txt = file_get_contents($this->app->basepath . "/vendor/max/framework/src/core/Templates/{$templateName}.php");
	    $txt = str_replace('INSERTNAMEHERE', $name, $txt);

	    $myfile = fopen($this->app->basepath . $save . $fileName, "w") or die("Unable to open file!");
	    fwrite($myfile, $txt);
	    fclose($myfile);
	}

}