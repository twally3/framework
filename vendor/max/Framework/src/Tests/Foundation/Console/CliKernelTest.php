<?php

require_once '../../../Core/Foundation/Console/CliKernel.php';
require_once '../../MockClasses/AppMock.php';

Class CliKernelTest extends PHPUnit_framework_Testcase {

	protected $app;

	protected $args = [
		'placeholder',
		'make:controller',
		'Banter'
	];

	public function setUp() {
		$this->app = new Framework\Core\Foundation\Console\CliKernel(
			new Framework\Core\Foundation\Application,
			false
		);
	}

	public function testCleanArgsReturnsArray() {
		$test = ['Banter'];
		$this->assertEquals($test, $this->app->cleanArgs($this->args));
	}

}