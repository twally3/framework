<?php

Class ContainerTest extends PHPUnit_Framework_Testcase {

	protected $container;

	public function setUp() {
		require_once '../../Core/Exceptions/DependencyDoesNotExistException.php';
		require_once '../../Core/Exceptions/DependencyNameAlreadyInUseException.php';
		require_once '../../Core/Foundation/Container.php';
		$this->container = new Framework\Core\Foundation\Container;
	}

	public function testObjectCanBeBoundToContainer() {
		$this->container->bind('foo', 'Bar');
		$this->assertEquals('Bar', $this->container->getBinding('foo')['value']);
	}

	public function testSingletonCanBeBoundToContainer() {
		$this->container->singleton('foo', 'Bar');
		$this->assertEquals('Bar', $this->container->getBinding('foo')['value']);
	}

	public function testReturnsNullWhenBindingNotFound() {
		$this->assertNull($this->container->getBinding('bar'));
	}

	public function testisSingletonReturnsTrueIfExists() {
		$this->container->singleton('foo', 'Bar');
		$this->assertTrue($this->container->isSingleton('foo'));
	}

	public function testSingletonResolvedReturnsTrue() {
		$this->container->singleton('foo', 'Bar');
		$this->container->resolve('foo');
		$this->assertTrue($this->container->singletonResolved('foo'));
	}

	public function testGetSingletonInstanceReturnsObject() {
		$this->container->singleton('foo', 'Bar');
		$this->container->resolve('foo');
		$object = $this->container->getSingletonInstance('foo');
		$this->assertInstanceOf('Bar', $object);
	}

	public function testGetSingletonInstanceReturnsNullIfNotExists() {
		$this->assertNull($this->container->getSingletonInstance('foo'));
	}

	public function testResolveClassReturnsObject() {
		$this->container->bind('bar', 'Bar');
		$object = $this->container->resolve('bar');
		$this->assertInstanceOf('Bar', $object);
	}

	public function testArrayAccessWorksAsIntended() {
		$this->container['qux'] = 'Bar';
		$object = $this->container['qux'];
		$this->assertInstanceOf('Bar', $object);
	}

	public function testExceptionThrownIfDependenyDoesntExist() {
		$this->expectException(Framework\Core\Exceptions\DependencyDoesNotExistException::class);
		$this->container->resolve('foo');
	}

	public function testResolveRecursivelyLoadsDependencies() {
		$this->container->bind('bar', 'Bar');
		$this->container->bind('foo', 'Foo');
		$object = $this->container->resolve('foo');
		$this->assertInstanceOf('Foo', $object);
	}

	public function testDependencyCannotBeOverwritten() {
		$this->expectException(Framework\Core\Exceptions\DependencyNameAlreadyInUseException::class);
		$this->container->bind('foo', 'Foo');
		$this->container->bind('foo', 'Bar');
	}

	public function testGetKeyFromValReturnsKey() {
		$this->container->bind('bar', 'Bar');
		$this->assertEquals('bar', $this->container->getKeyFromVal('Bar'));
	}

	public function testGetKeyFromValThrowsException() {
		$this->expectException(Framework\Core\Exceptions\DependencyDoesNotExistException::class);
		$this->container->getKeyFromVal('foo');
	}

}

Class Bar {

}

class Foo {
	public function __construct(Bar $bar) {

	}
}