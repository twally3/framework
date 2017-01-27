<?php

require_once '../../Core/HTTP/Validator.php';
require_once '../../Core/Foundation/Container.php';
require_once '../../Core/Foundation/Application.php';
require_once '../../Core/Render/Tea.php';
require_once '../../Core/HTTP/Controller.php';

Class ControllerTest extends PHPUnit_framework_Testcase {

	protected $app;

	function setUp() {
		$this->app = new Framework\Core\HTTP\Controller(
			$this->createMock(Framework\Core\Foundation\Application::class),
			$this->createMock(Framework\Core\Render\Tea::class),
			$this->createMock(Framework\Core\HTTP\Validator::class)
		);
	}
}