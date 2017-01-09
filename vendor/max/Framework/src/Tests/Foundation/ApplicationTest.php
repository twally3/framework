<?php

require_once '../../Core/Exceptions/DependencyDoesNotExistException.php';
require_once '../../Core/Exceptions/DependencyNameAlreadyInUseException.php';
require_once '../../Core/Support/ServiceProviderInterface.php';
require_once '../../Core/Foundation/Container.php';
require_once '../../Core/Foundation/Application.php';

Class ApplicationTest extends PHPUnit_Framework_Testcase {

	protected $config = [
		'providers' => [],
		'aliases' => []
	];

	protected $app;

	public function setUp() {
		$this->app = new Framework\Core\Foundation\Application(
			realpath(__DIR__ . '/../../../../../../'),
			$this->config
		);
	}

	public function testBasepathIsSetCorrectly() {
		$path = realpath(__DIR__ . '/../../../../../../');
		$this->assertEquals($path, $this->app->basepath);
	}

	public function testRegisterProvidersReturnsAppInstance() {
		$object = $this->app->registerProvider(new QuxProvider);
		$this->assertInstanceOf('Framework\Core\Foundation\Application', $object);
	}

	public function testProviderHasBeenLoadedReturnsTrue() {
		$this->app->registerProvider(new QuxProvider);
		$this->assertTrue($this->app->providerHasBeenLoaded(new QuxProvider));
	}

}

use Framework\Core\Support\ServiceProviderInterface;
use Framework\Core\Foundation\Application;

class QuxProvider implements ServiceProviderInterface {
	public function register (Application $app) {
		// $app->bind('qux', 'App\Bootstrap\Includes\Qux');
	}
}