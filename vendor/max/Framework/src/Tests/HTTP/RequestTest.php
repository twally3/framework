<?php

require_once '../../Core/Support/ServiceProviderInterface.php';
require_once '../../Core/Foundation/Container.php';
require_once '../../Core/Foundation/Application.php';
require_once '../../Core/HTTP/Request.php';

Class RequestTest extends PHPUnit_framework_Testcase {

	protected $requests = [
		'test' => '123',
		'test2' => '456'
	];

	public function setUp() {
		foreach ($this->requests as $key => $request) {
			$_GET[$key] = $request;
		}

		$app = $this->createMock(Framework\Core\Foundation\Application::class);
		$this->app = new Framework\Core\HTTP\Request(
			$app
		);
	}

	public function test__getReturnsExistingValue() {
		$this->assertEquals($this->app->test, '123');
	}

	public function test__getReturnsNullWhenKeyDoesNotExist() {
		$this->assertNull($this->app->idontexist);
	}

	public function testGetMethodReturnsServerMethod() {
		$this->assertEquals($this->app->getMethod(), $_SERVER['REQUEST_METHOD']);
	}

	public function testAllReturnsAllBoundMethods() {
		$this->assertEquals($this->app->all(), $this->requests);
	}

	public function testPathReturnsCorrectURL() {
		$this->assertEquals($this->app->path(), parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH));
	}

	public function testIsReturnsTrue() {
		$this->assertTrue($this->app->is(''));
	}

	public function testIsReturnsFalse() {
		$this->assertFalse($this->app->is('thing'));
	}

	public function testIsMethodReturnsTrue() {
		$this->assertTrue($this->app->isMethod(''));
	}

	public function testIsMethodReturnsFalse() {
		$this->assertFalse($this->app->isMethod('thing'));
	}

	public function testInputReturnsValueIfItExists() {
		$this->assertEquals($this->app->input('test'), $this->requests['test']);
	}

	public function testInputReturnsNullByDefaultIfValueDoesntExist() {
		$this->assertNull($this->app->input('thing'));
	}

	public function testInputReturnsCustomDefaultValueWhenSpecified() {
		$this->assertEquals($this->app->input('thing', 'return'), 'return');
	}

	public function testOnlyReturnsNullWhenNoPropertiesAreSpecified() {
		$this->assertNull($this->app->only());
	}

	public function testOnlyReturnsSpecifiedValue() {
		$this->assertEquals($this->app->only('test'), ['test' => '123']);
	}

	public function testOnlyReturnsSpecifiedValues() {
		$this->assertEquals($this->app->only('test', 'test2'), ['test' => '123', 'test2' => '456']);
	}

	public function testOnlyReturnsValuesWhenGivenAnArray() {
		$this->assertEquals($this->app->only(['test', 'test2']), ['test' => '123', 'test2' => '456']);
	}

	public function testOnlyReturnsNullWhenKeyDoesntExist() {
		$this->assertEquals($this->app->only('test3'), ['test3' => null]);
	}

	public function testExcludeReturnsAllValuesWhenNoneAreSpecified() {
		$this->assertEquals($this->app->exclude(), $this->requests);
	}

	public function testExcludeReturnsArrayWithoutSpecificKey() {
		$this->assertEquals($this->app->exclude('test'), ['test2' => '456']);
	}

	public function testExcludeReturnsArrayWithoutSpecificKeys() {
		$this->assertEquals($this->app->exclude('test', 'test2'), []);
	}

	public function testExcludeReturnsArrayWithoutSpecificKeyWhenGivenAnArray() {
		$this->assertEquals($this->app->exclude(['test', 'test2']), []);
	}

	public function testHasReturnsTrueWhenValueExists() {
		$this->assertTrue($this->app->has('test'));
	}

	public function testHasReturnsFalseWhenValueIsMissing() {
		$this->assertFalse($this->app->has('thing'));
	}

	public function testFlashBindsToSession() {
		$this->app->flash();
		$this->assertEquals($_SESSION['flash']['request'], $this->requests);
	}

	public function testFlashOnlyReturnsNullWhenNoPropertiesAreSpecified() {
		$this->assertNull($this->app->flashOnly());
	}

	public function testFlashOnlyReturnsSpecifiedValue() {
		$this->app->flashOnly('test');
		$session = $_SESSION['flash']['request'];
		$this->assertEquals($session, ['test' => '123']);
	}

	public function testFlashOnlyReturnsSpecifiedValues() {
		$this->app->flashOnly('test', 'test2');
		$session = $_SESSION['flash']['request'];
		$this->assertEquals($session, ['test' => '123', 'test2' => '456']);
	}

	public function testFlashOnlyReturnsValuesWhenGivenAnArray() {
		$this->app->flashOnly(['test', 'test2']);
		$session = $_SESSION['flash']['request'];
		$this->assertEquals($session, ['test' => '123', 'test2' => '456']);
	}

	public function testFlashOnlyReturnsNullWhenKeyDoesntExist() {
		$this->app->flashOnly('test3');
		$session = $_SESSION['flash']['request'];
		$this->assertEquals($session, ['test3' => null]);
	}

	public function testFlashExcludeReturnsAllValuesWhenNoneAreSpecified() {
		$this->app->flashExclude();
		$session = $_SESSION['flash']['request'];
		$this->assertEquals($session, $this->requests);
	}

	public function testFlashExcludeReturnsArrayWithoutSpecificKey() {
		$this->app->flashExclude('test');
		$session = $_SESSION['flash']['request'];
		$this->assertEquals($session, ['test2' => '456']);
	}

	public function testFlashExcludeReturnsArrayWithoutSpecificKeys() {
		$this->app->flashExclude('test', 'test2');
		$session = $_SESSION['flash']['request'];
		$this->assertEquals($session, []);
	}

	public function testFlashExcludeReturnsArrayWithoutSpecificKeyWhenGivenAnArray() {
		$this->app->flashExclude(['test', 'test2']);
		$session = $_SESSION['flash']['request'];
		$this->assertEquals($session, []);
	}

	public function testOldReturnsFlashedSession() {
		$this->app->flash();
		$this->assertEquals($this->app->old(), $this->requests);
	}

	public function testOldEmptiesFlashedSession() {
		$this->app->flash();
		$this->app->old();
		$this->assertEquals($_SESSION['flash']['request'], []);
	}

	public function testOldReturnsSpecifiedValue() {
		$this->app->flash();
		$this->assertEquals($this->app->old('test'), '123');
	}

	public function testOldEmptiesSpecifiedFlashedSession() {
		$this->app->flash();
		$this->app->old('test');
		$this->assertNull($_SESSION['flash']['request']['test']);
	}

	public function testOldOnlyEmptiesSpecifiedFlashedSession() {
		$this->app->flash();
		$this->app->old('test');
		$this->assertEquals($_SESSION['flash']['request'], ['test2' => '456']);
	}

	public function testEmptyFlashClearsEntireSession() {
		$this->app->flash();
		$this->app->emptyFlash();
		$this->assertEquals($_SESSION['flash']['request'], []);
	}

}