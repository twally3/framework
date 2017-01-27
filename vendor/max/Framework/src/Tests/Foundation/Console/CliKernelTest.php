<?php

require_once '../../../Core/Foundation/Console/CliKernel.php';
require_once '../../../Core/Support/ServiceProviderInterface.php';
require_once '../../../Core/Foundation/Container.php';
require_once '../../../Core/Foundation/Application.php';

Class CliKernelTest extends PHPUnit_framework_Testcase {

	protected $app;

	protected $args = [
		'placeholder',
		'make:controller',
		'Banter'
	];

	public function setUp() {
		$this->app = new Framework\Core\Foundation\Console\CliKernel(
			$this->createMock(Framework\Core\Foundation\Application::class),
			false
		);
	}

	public function testCleanArgsReturnsArray() {
		$test = ['Banter'];
		$this->assertEquals($test, $this->app->cleanArgs($this->args));
	}

}