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

	public function testStoreReturnsFalse() {
		$this->assertFalse($this->app->store('', 'name.png'));
	}

	public function testFileSizeEqualToReturnsTrue() {
		$this->assertTrue($this->app->fileSizeEqualTo(1590218));
	}

	public function testFileSizeEqualToReturnsFalse() {
		$this->assertFalse($this->app->fileSizeEqualTo(1590219));
	}

	public function testFileSizeGreaterThanReturnsTrue() {
		$this->assertTrue($this->app->fileSizeGreaterThan(5));
	}

	public function testFileSizeGreaterThanReturnsFalse() {
		$this->assertFalse($this->app->fileSizeGreaterThan(2000000));
	}

	public function testFileSizeGreaterThanReturnsFalseWhenEqualsToIsFalse() {
		$this->assertFalse($this->app->fileSizeGreaterThan(1590218));
	}

	public function testFileSizeGreaterThanWithEqualsToReturnsTrue() {
		$this->assertTrue($this->app->fileSizeGreaterThan(1590218, true));
	}

	public function testFileSizeLessThanReturnsTrue() {
		$this->assertTrue($this->app->fileSizeLessThan(2000000));
	}

	public function testFileSizeLessThanReturnsFalse() {
		$this->assertFalse($this->app->fileSizeLessThan(5));
	}

	public function testFileSizeLessThanReturnsFalseWhenEqualsToIsFalse() {
		$this->assertFalse($this->app->fileSizeLessThan(1590218));
	}

	public function testFileSizeLessThanWithEqualsToReturnsTrue() {
		$this->assertTrue($this->app->fileSizeLessThan(1590218, true));
	}

	public function testHasExtentionReturnsTrueWithOneParam() {
		$this->assertTrue($this->app->hasExtention('jpg'));
	}

	public function testHasExtentionReturnsTrueWithMultipleParams() {
		$this->assertTrue($this->app->hasExtention('jpg', 'png'));
	}

	public function testHasExtentionReturnsFalseWithOneParam() {
		$this->assertFalse($this->app->hasExtention('png'));
	}

	public function testHasExtentionReturnsFalseWithMultipleParams() {
		$this->assertFalse($this->app->hasExtention('gif', 'png'));
	}

	public function testHasExtentionReturnsTrueWithOneParamAsArrays() {
		$this->assertTrue($this->app->hasExtention(['jpg']));
	}

	public function testHasExtentionReturnsTrueWithMultipleParamsAsArrays() {
		$this->assertTrue($this->app->hasExtention(['jpg', 'png']));
	}

	public function testHasExtentionReturnsFalseWithOneParamAsArrays() {
		$this->assertFalse($this->app->hasExtention(['png']));
	}

	public function testHasExtentionReturnsFalseWithMultipleParamsAsArrays() {
		$this->assertFalse($this->app->hasExtention(['gif', 'png']));
	}

}