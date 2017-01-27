<?php

require_once '../../Core/HTTP/Request.php';
require_once '../../Core/HTTP/Validator.php';
require_once '../../Core/Database/Database.php';

Class ValidatorTest extends PHPUnit_framework_Testcase {

	public function __construct() {
		$this->request = $this->createMock(Framework\Core\HTTP\Request::class);

		$this->request->max1 = '12345';
		$this->request->isBool = true;
		$this->request->false = false;
		$this->request->notBool = 'hello world';
		$this->request->int = 123;
		$this->request->int2 = 10;
		$this->request->email = 'email@email.com';
		$this->request->pwrd1 = 'thing';
		$this->request->pwrd2 = 'thing';

		$db = $this->createMock(Framework\Core\Database\Database::class);
		$db->method('select')->willReturn(new Data);

		$this->app = new Framework\Core\HTTP\Validator($db);
	}

	public function testCheckReturnsTrue() {
		$tests = [
			'max1' => 'max:5'
		];

		$this->assertTrue($this->app->check($this->request, $tests));
	}

	public function testCheckOnlyHasOneFailByDefault() {
		$tests = [
			'max1' => 'max:2',
			'email' => 'alphanumeric'
		];

		$this->app->check($this->request, $tests);
		$this->assertEquals(count($this->app->fails()), 1);
	}

	public function testCheckHasManyFailsWhenSpecified() {
		$tests = [
			'max1' => 'max:2',
			'email' => 'alphanumeric'
		];

		$this->app->check($this->request, $tests, false);
		$this->assertEquals(count($this->app->fails()), 2);
	}

	public function testCheckReturnsFalseWhenFastIsSetToFalse() {
		$tests = [
			'max1' => 'max:2',
			'email' => 'alphanumeric'
		];
		$this->assertFalse($this->app->check($this->request, $tests, false));
	}

	
	// --------------------Max-------------------- //
	public function testMaxReturnsTrue() {
		$tests = [
			'max1' => 'max:5'
		];

		$this->assertTrue($this->app->check($this->request, $tests));
	}

	public function testMaxThrowsExceptionWhenParamIsNotPresent() {
		$tests = [
			'max1' => 'max'
		];
		$this->expectException(\Exception::class);
		$this->app->check($this->request, $tests);
	}

	public function testMaxReturnsFalseWhenCriteriaIsNotMet() {
		$tests = [
			'max1' => 'max:4'
		];

		$this->assertFalse($this->app->check($this->request, $tests));
	}

	// --------------------Max-------------------- //
	public function testMinReturnsTrue() {
		$tests = [
			'max1' => 'min:5'
		];

		$this->assertTrue($this->app->check($this->request, $tests));
	}

	public function testMinThrowsExceptionWhenParamIsNotPresent() {
		$tests = [
			'max1' => 'min'
		];
		$this->expectException(\Exception::class);
		$this->app->check($this->request, $tests);
	}

	public function testMinReturnsFalseWhenCriteriaIsNotMet() {
		$tests = [
			'max1' => 'min:6'
		];

		$this->assertFalse($this->app->check($this->request, $tests));
	}

	public function testIsBoolReturnTrueWithValidResult() {
		$tests = [
			'isBool' => 'bool'
		];

		$this->assertTrue($this->app->check($this->request, $tests));
	}

	public function testIsBoolReturnsFalseWhenParamIsNotOfTypeBool() {
		$tests = [
			'notBool' => 'bool'
		];

		$this->assertFalse($this->app->check($this->request, $tests));
	}

	public function testIsIntegerReturnsTrueWhenIntIsPassed() {
		$tests = [
			'int' => 'int'
		];

		$this->assertTrue($this->app->check($this->request, $tests));
	}

	public function testIsIntegerReturnsFalseWhenNotBoolean() {
		$tests = [
			'notBool' => 'int'
		];

		$this->assertFalse($this->app->check($this->request, $tests));
	}

	public function testIsStringReturnsTrueWhenStringIsPassed() {
		$tests = [
			'notBool' => 'string'
		];

		$this->assertTrue($this->app->check($this->request, $tests));
	}

	public function testIsStringReturnsFalseWhenNotString() {
		$tests = [
			'isBool' => 'string'
		];

		$this->assertFalse($this->app->check($this->request, $tests));
	}

	public function testIsNullReturnsTrueForNull() {
		$tests = [
			'geoff' => 'null'
		];

		$this->assertTrue($this->app->check($this->request, $tests));
	}

	public function testIsNullReturnsFalseWhenNotNull() {
		$tests = [
			'isBool' => 'null'
		];

		$this->assertFalse($this->app->check($this->request, $tests));
	}

	// -----------------Is Before----------------- //
	public function testIsBeforeReturnsTrue() {
		$tests = [
			'int' => 'before:10'
		];

		$this->assertTrue($this->app->check($this->request, $tests));
	}

	public function testIsBeforeThrowsExceptionWhenParamIsNotPresent() {
		$tests = [
			'int' => 'before'
		];
		$this->expectException(\Exception::class);
		$this->app->check($this->request, $tests);
	}

	public function testIsBeforeReturnsFalseWhenCriteriaIsNotMet() {
		$tests = [
			'int' => 'before:200'
		];

		$this->assertFalse($this->app->check($this->request, $tests));
	}

	// -----------------Is After----------------- //
	public function testIsAfterReturnsTrue() {
		$tests = [
			'int' => 'after:200'
		];

		$this->assertTrue($this->app->check($this->request, $tests));
	}

	public function testIsAfterThrowsExceptionWhenParamIsNotPresent() {
		$tests = [
			'int' => 'before'
		];
		$this->expectException(\Exception::class);
		$this->app->check($this->request, $tests);
	}

	public function testIsAfterReturnsFalseWhenCriteriaIsNotMet() {
		$tests = [
			'int' => 'after:10'
		];

		$this->assertFalse($this->app->check($this->request, $tests));
	}

	// -----------------Is Accepted----------------- //
	public function testIsAcceptedReturnsTrue() {
		$tests = [
			'isBool' => 'accepted'
		];

		$this->assertTrue($this->app->check($this->request, $tests));
	}

	public function testIsAcceptedThrowsExceptionWhenParamIsNotPresent() {
		$tests = [
			'max1' => 'accepted'
		];
		$this->expectException(\Exception::class);
		$this->app->check($this->request, $tests);
	}

	public function testIsAcceptedReturnsFalseWhenCriteriaIsNotMet() {
		$tests = [
			'false' => 'accepted'
		];

		$this->assertFalse($this->app->check($this->request, $tests));
	}

	// -----------------Is Required----------------- //
	public function testIsRequiredReturnsTrueWhenSet() {
		$tests = [
			'int' => 'required'
		];

		$this->assertTrue($this->app->check($this->request, $tests));
	}

	public function testIsRequiredReturnsFalseWhenNull() {
		$tests = [
			'geoff' => 'required'
		];

		$this->assertFalse($this->app->check($this->request, $tests));
	}

	// -----------------Does Match----------------- //
	public function testDoesMatchReturnsTrueWhenPropertiesMatch() {
		$tests = [
			'pwrd2' => 'matches:pwrd1'
		];
		
		$this->assertTrue($this->app->check($this->request, $tests));
	}

	public function testDoesMatchReturnFalseWhenPropertyDoesNotExist() {
		$tests = [
			'pwrd' => 'matches:pwrd1'
		];

		$this->assertFalse($this->app->check($this->request, $tests));
	}

	public function testDoesMatchReturnFalseWhenPropertyDoesNotMatch() {
		$tests = [
			'pwrd2' => 'matches:notBool'
		];

		$this->assertFalse($this->app->check($this->request, $tests));
	}

	// -----------------Email----------------- //
	public function testUniqueReturnsFalseWhenMatch() {
		$tests = [
			'pwrd2' => 'unique:users,email'
		];

		$this->assertFalse($this->app->check($this->request, $tests));
	}

	public function testUniqueReturnsTrueWhenNoMatch() {
		$tests = [
			'pwrd2' => 'unique:users,email'
		];

		$db = $this->createMock(Framework\Core\Database\Database::class);
		$db->method('select')->willReturn(new NoData);

		$app = new Framework\Core\HTTP\Validator($db);

		$this->assertTrue($app->check($this->request, $tests));
	}

	// -----------------Email----------------- //
	public function testEmailReturnsTrue() {
		$tests = [
			'email' => 'email'
		];

		$this->assertTrue($this->app->check($this->request, $tests));
	}

	public function testEmailReturnsFalse() {
		$tests = [
			'notBool' => 'email'
		];

		$this->assertFalse($this->app->check($this->request, $tests));
	}

	// -----------------Email----------------- //
	public function testAlphaReturnsTrue() {
		$tests = [
			'notBool' => 'alphanumeric'
		];

		$this->assertTrue($this->app->check($this->request, $tests));
	}

	public function testAlphaReturnsFalse() {
		$tests = [
			'email' => 'alphanumeric'
		];

		$this->assertFalse($this->app->check($this->request, $tests));
	}
}


Class Data {
	public function fetchAll() {
		return [
			'somematch'
		];
	}
}

Class NoData {
	public function fetchAll() {
		return [];
	}
}
