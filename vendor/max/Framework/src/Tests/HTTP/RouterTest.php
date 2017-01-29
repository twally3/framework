<?php

require_once '../../Core/Support/ServiceProviderInterface.php';
require_once '../../Core/Foundation/Container.php';
require_once '../../Core/Foundation/Application.php';
require_once '../../Core/HTTP/Router.php';

class RouterTest extends PHPUnit_framework_Testcase {

	public function setUp() {
		$app = $this->createMock(Framework\Core\Foundation\Application::class);
		$app->basepath = realpath(__DIR__ . '/../../../../../../');
		
		$this->app = new Framework\Core\HTTP\Router(
			$app
		);
	}

	public function testGetReturnsInstanceOfRouter() {
		$x = $this->app->get('/', 'home@index');
		$this->assertInstanceOf('Framework\Core\HTTP\Router', $x);
	}

	public function testGetReturnsNullWhenNoRouteIsProvided() {
		$x = $this->app->get('/');
		$this->assertNull($x);
	}

	public function testCorrectRouteIsReturnedWhenMethodIsGet() {
		$_GET['uri'] = '';
		$_SERVER['REQUEST_METHOD'] = 'get';

		$this->app->get('/', function(){return True;});

		$x = $this->app->submit()[0];

		$this->assertTrue($x());
	}

	public function testPostReturnsInstanceOfRouter() {
		$x = $this->app->post('/', 'home@index');
		$this->assertInstanceOf('Framework\Core\HTTP\Router', $x);
	}

	public function testPostReturnsNullWhenNoRouteIsProvided() {
		$x = $this->app->post('/');
		$this->assertNull($x);
	}

	public function testCorrectRouteIsReturnedWhenMethodIsPost() {
		$_GET['uri'] = '';
		$_SERVER['REQUEST_METHOD'] = 'post';

		$this->app->post('/', function(){return True;});

		$x = $this->app->submit()[0];

		$this->assertTrue($x());
	}

	public function testPutReturnsInstanceOfRouter() {
		$x = $this->app->put('/', 'home@index');
		$this->assertInstanceOf('Framework\Core\HTTP\Router', $x);
	}

	public function testPutReturnsNullWhenNoRouteIsProvided() {
		$x = $this->app->put('/');
		$this->assertNull($x);
	}

	public function testCorrectRouteIsReturnedWhenMethodIsPut() {
		$_GET['uri'] = '';
		$_SERVER['REQUEST_METHOD'] = 'post';
		$_POST['_method'] = 'put';

		$this->app->put('/', function(){return True;});

		$x = $this->app->submit()[0];

		$this->assertTrue($x());
	}

	public function testDeleteReturnsInstanceOfRouter() {
		$x = $this->app->delete('/', 'home@index');
		$this->assertInstanceOf('Framework\Core\HTTP\Router', $x);
	}

	public function testDeleteReturnsNullWhenNoRouteIsProvided() {
		$x = $this->app->delete('/');
		$this->assertNull($x);
	}

	public function testCorrectRouteIsReturnedWhenMethodIsDelete() {
		$_GET['uri'] = '';
		$_SERVER['REQUEST_METHOD'] = 'post';
		$_POST['_method'] = 'delete';

		$this->app->delete('/', function(){return True;});

		$x = $this->app->submit()[0];

		$this->assertTrue($x());
	}

	public function testNameBindsCorrectly() {
		$this->app->get('/', 'home@index')->name('test');
		$this->assertEquals($this->app->path('test'), '/');
	}

	public function testPathReturnsCorrectRoute() {
		$this->app->get('/test', 'home@index')->name('test');
		$this->assertEquals($this->app->path('test'), '/test');
	}

	public function testPathReturnsCorrectRouteWithParams() {
		$this->app->get('/test/{thing}', 'home@index')->name('test');
		$this->assertEquals($this->app->path('test',['value']), '/test/value');
	}

	public function testPathReturnsNullForRoutesWithParamsThatArentPassed() {
		$this->app->get('/test/{thing}', 'home@index')->name('test');
		$this->assertNull($this->app->path('test'));
	}

	public function testPathReturnsNullForRoutesWithParamsThatArentPassedss() {
		$this->app->get('/test/?{thing}', 'home@index')->name('test');
		$this->assertNull($this->app->path('test'));
	}

	public function testGroupRunsCallback() {
		$this->app->group([], function() {
			$this->app->get('/test', 'home@test')->name('testing');
		});

		$this->assertEquals($this->app->path('testing'), '/test');
	}

	public function testGroupAddsPrefix() {
		$this->app->group(['prefix' => '/prefix'], function() {
			$this->app->get('/test', 'home@test')->name('testing2');
		});

		$this->assertEquals($this->app->path('testing2'), '/prefix/test');
	}

	public function testGroupAddsMiddleware() {
		$_GET['uri'] = '';
		$_SERVER['REQUEST_METHOD'] = 'get';

		$this->app->group(['middleware' => 'auth'], function() {
			$this->app->get('/', function(){});
		});

		$x = $this->app->submit()[2];

		$this->assertEquals($x, ['auth']);
	}

	public function testSubmitThrowsPageDoesNotExistException() {
		$this->expectException(\Exception::class);
		$this->app->get('/', 'home@test');
		$this->app->submit();
	}

	public function testSubmitThrowsPageDoesNotExistExceptionWhenGivenALoadOfShit() {
		$this->expectException(\Exception::class);
		$this->app->get('/', 12345);
		$this->app->submit();
	}


	public function testSubmitReturnsArrayWhenPageExists() {
		$_GET['uri'] = '';
		$_SERVER['REQUEST_METHOD'] = 'get';

		$this->app->get('/', function() {
			echo 'hey';
		});
		$this->assertInternalType('array', $this->app->submit());
	}

	public function testSubmitReturnsArrayWithCallback() {
		$_GET['uri'] = '';
		$_SERVER['REQUEST_METHOD'] = 'get';

		$this->app->get('/', function() {
			return 'HELL YEAH';
		});
		$callback = $this->app->submit()[0];
		$x = $callback();
		$this->assertEquals($x, 'HELL YEAH');
	}

	public function testSubmitReturnsArrayWithProperties() {
		$_GET['uri'] = 'thing/thingy';
		$_SERVER['REQUEST_METHOD'] = 'get';

		$this->app->get('/thing/{x}', function() {
			return 'HELL YEAH';
		});
		$x = $this->app->submit()[1];

		$this->assertEquals($x, ['thingy']);
	}

	public function testSubmitReturnsArrayWithEmptyParamsWhenNoneAreSet() {
		$_GET['uri'] = '';
		$_SERVER['REQUEST_METHOD'] = 'get';

		$this->app->get('/', function() {
			return 'HELL YEAH';
		});
		$x = $this->app->submit()[1];

		$this->assertEquals($x, []);
	}

	public function testSubmitReturnsMiddlewareWhenSet() {
		$_GET['uri'] = '';
		$_SERVER['REQUEST_METHOD'] = 'get';

		$this->app->get('/', function() {
			return 'HELL YEAH';
		})->middleware(['auth']);
		$x = $this->app->submit()[2];

		$this->assertEquals($x, ['auth']);
	}

	public function testSubmitReturnsNullWhenMiddlewareNotSet() {
		$_GET['uri'] = '';
		$_SERVER['REQUEST_METHOD'] = 'get';

		$this->app->get('/', function() {
			return 'HELL YEAH';
		});
		$x = $this->app->submit()[2];

		$this->assertNull($x);
	}

}