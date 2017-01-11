<?php

require_once '../MockClasses/TeaMock.php';
require_once '../MockClasses/AppMock.php';
require_once '../MockClasses/ValidatorMock.php';
require_once '../../Core/HTTP/Controller.php';

Class ControllerTest extends PHPUnit_framework_Testcase {

	protected $app;

	function setUp() {
		$this->app = new Framework\Core\HTTP\Controller(
			new Framework\Core\Foundation\Application,
			new Framework\Core\Render\Tea,
			new Framework\Core\HTTP\Validator
		);
	}
}