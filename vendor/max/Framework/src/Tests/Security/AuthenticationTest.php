<?php

require_once '../../Core/Database/Database.php';
require_once '../../Core/Security/JWT.php';
require_once '../../Core/Security/Authentication.php';

class AuthenticationTest extends PHPUnit_framework_testcase {

	public function setUp() {
		$this->db = $this->createMock(Framework\Core\Database\Database::class);
		$this->jwt = $this->createMock(Framework\Core\Security\JWT::class);

		$this->app = new Framework\Core\Security\Authentication(
			$this->db,
			$this->jwt
		);
	}

	public function testAttemptReturnsTrue() {
		$x = [
			'email' => 'max@cakerstream.com',
			'password' => 'password'
		];

		$this->db->method('select')->willReturn(new Select);
		$this->assertTrue($this->app->attempt($x));
	}

	public function testAttemptSetsSession() {
		$x = [
			'email' => 'max@cakerstream.com',
			'password' => 'password'
		];

		$this->db->method('select')->willReturn(new Select);
		$this->app->attempt($x);

		$this->assertEquals($_SESSION, ['user' => 1, 'token' => '1c3232f2718731206c9e3e3c1bd42e17']);
	}

	public function testAttemptReturnsFalseWhenPasswordIsIncorrect() {
		$x = [
			'email' => 'max@cakerstream.com',
			'password' => 'notthepassword'
		];

		$this->db->method('select')->willReturn(new Select);
		$this->assertFalse($this->app->attempt($x));
	}

	// public function testAttemptReturnsFalseWhenUserDoesntExist() {
	// 	$x = [
	// 		'email' => 'mmmmmmax@cakerstream.com',
	// 		'password' => 'password'
	// 	];

	// 	$this->db->method('select')->willReturn(new Select);
	// 	$this->assertFalse($this->app->attempt($x));
	// }

	public function testLogoutReturnsTrue() {
		$this->assertTrue($this->app->logout());
	}

}

class Select {
	public function fetchAll() {
		$arr = [
			'id' => 1,
			'name' => 'Max Taylor',
			'email' => 'max@cakerstream.com',
			'password' => '$2y$10$iuMEhPcLpU/6riUy9X/QTOMhgo28ON6wFdvfAlz1wsNmaHhWHKYXK',
			'created_at' => 1485862830,
			'updated_at' => 1485862830
		];

		$obj = (object) $arr;

		return [$obj];
	}
}