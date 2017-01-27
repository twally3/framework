<?php

require_once '../../Core/HTTP/FileRequest.php';

class FileRequestTest extends PHPUnit_framework_Testcase {

	protected $file = [
		'file' => [
			'name' => 'grand-central.jpg',
			'type' => 'image/jpeg',
			'tmp_name' => '/Applications/MAMP/tmp/php/phpO0XY60',
			'error' => 0,
			'size' => 1590218
		],
	];
	
	public function setUp() {
		$this->app = new Framework\Core\HTTP\FileRequest($this->file['file'], true);
	}

	public function testGetNameReturnsName() {
		$this->assertEquals($this->app->getName(), $this->file['file']['name']);
	}

	public function testGetOriginalNameReturnsOriginalName() {
		$this->assertEquals($this->app->getOriginalName(), $this->file['file']['name']);
	}

	public function testIsValidReturnsTrueWithNoErrors() {
		$this->assertTrue($this->app->isValid());
	}

	public function testMoveUploadedFileReturnsFalse() {
		
	}

}