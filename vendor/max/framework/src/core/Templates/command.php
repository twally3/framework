<?php

namespace App\Bootstrap\Commands;

use Framework\Core\Support\CommandInterface;
use Framework\Core\Foundation\Application;

Class INSERTNAMEHERE implements CommandInterface {

	protected $app;

	public function __construct(Application $app) {

		$this->app = $app;

	}

	public function handle($args) {

	}

}