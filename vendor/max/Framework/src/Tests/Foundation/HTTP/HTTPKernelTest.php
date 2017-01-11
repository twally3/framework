<?php

require_once '../../../Core/Foundation/HTTP/HTTPKernel.php';
require_once './RequestMock.php';
require_once '../../MockClasses/AppMock.php';

class HTTPKernelTest extends PHPUnit_framework_Testcase {

	protected $app;

	protected $sessions = [

	];

	function setUp() {
		$this->app = new Framework\Core\Foundation\HTTP\HTTPKernel(
			new Framework\Core\Foundation\Application,
			new Framework\Core\HTTP\Router,
			$this->sessions,
			false
		);
	}

	function testHandleReturnsResponse() {
		$this->assertEquals('response', $this->app->handle(new Framework\Core\HTTP\Request));
	}

}