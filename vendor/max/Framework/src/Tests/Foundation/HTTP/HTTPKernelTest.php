<?php

require_once '../../../Core/Foundation/HTTP/HTTPKernel.php';
require_once '../../../Core/HTTP/Request.php';
require_once '../../../Core/HTTP/Router.php';
require_once '../../../Core/Foundation/Container.php';
require_once '../../../Core/Support/ServiceProviderInterface.php';
require_once '../../../Core/Foundation/Application.php';

class HTTPKernelTest extends PHPUnit_framework_Testcase {

	protected $app;

	protected $sessions = [

	];

	function setUp() {
		$callback = function() {
			echo 'hey';
		};
		$router = $this->createMock(Framework\Core\HTTP\Router::class);
		$router->method('submit')->willReturn([$callback, [], []]);

		$this->app = new Framework\Core\Foundation\HTTP\HTTPKernel(
			$this->createMock(Framework\Core\Foundation\Application::class),
			$router,
			$this->sessions,
			false
		);
	}

	function testHandleReturnsResponse() {
		$this->assertEquals('response', $this->app->handle($this->createMock(Framework\Core\HTTP\Request::class)));
	}

}