<?php

require '../../Core/HTTP/SessionRequest.php';

class SessionRequestTest extends PHPUnit_framework_Testcase {

	public function __construct() {
		$this->app = new Framework\Core\HTTP\SessionRequest();
	}

	public function testSessionIsSetReturnsTrueWhenSet() {
		$this->app->set('test', 'this is a test');
		$this->assertTrue($this->app->is_set('test'));
	}

	public function testSessionIsSetReturnsFalseWhenMissing() {
		$this->assertFalse($this->app->is_set('test'));
	}

	public function testSingleReturnsSessionInstance() {
		$text = 'this is a test';
		$this->app->set('test', $text);
		$this->assertEquals($this->app->single('test'), $text);
	}

	public function testSingleRemovesSessionInstance() {
		$this->app->set('test', 'this is a test');
		$this->app->single('test');
		$this->assertFalse($this->app->is_set('test'));
	}

	public function testGetReturnsSessionInstance() {
		$text = 'this is a test';
		$this->app->set('test', $text);
		$this->assertEquals($this->app->get('test'), $text);
	}

	public function testGetReturnsNullWhenSessionIsNotPresent() {
		$this->assertNull($this->app->get('test'));
	}


}