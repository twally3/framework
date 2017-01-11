<?php

namespace Framework\Core\Foundation;

use ReflectionClass;
use ArrayAccess;
use Framework\Core\Exceptions\DependencyNameAlreadyInUseException;
use Framework\Core\Exceptions\ClassIsNotInstantiableException;
use Framework\Core\Exceptions\DependencyDoesNotExistException;

class Container implements ArrayAccess {

	/**
	 * Container bindings
	 * @var array
	 */
	protected $bindings = [];

	/**
	 * Singleton instances
	 * @var array
	 */
	protected $instances = [];


	/**
	 * Bind a class reference to the container
	 * @param  string  $key       The reference name must be unique
	 * @param  string  $value     The class name with namespace
	 * @param  boolean $singleton Optional singleton
	 * @return void
	 */
	public function bind($key, $value, $singleton = false) {
		if (!is_null($this->getBinding($key))) throw new DependencyNameAlreadyInUseException('Dependency Name is already in use');

		$this->bindings[$key] = compact('value', 'singleton');
	}


	/**
	 * Bind a singleton to the cotnainer
	 * @param  string $key   The reference name must be unique
	 * @param  string $value The class name with namespace
	 * @return void
	 */
	public function singleton($key, $value) {
		return $this->bind($key, $value, true);
	}


	/**
	 * Gets the bound value from reference name
	 * @param  string $key Reference name
	 * @return string      The class name with namespace
	 */
	public function getBinding($key) {
		if (!array_key_exists($key, $this->bindings)) {
			return null;
		}

		return $this->bindings[$key];
	}


	/**
	 * Checks if the class is a singleton by reference
	 * @param  string  $key Reference name
	 * @return boolean      Is the class a singleton or not?
	 */
	public function isSingleton($key) {
		$binding = $this->getBinding($key);

		if ($binding === null) {
			return false;
		}

		return $binding['singleton'];
	}


	/**
	 * Checks if the singleton has already been stored
	 * @param  string $key  Reference name
	 * @return boolean      Has the singleton been resolved
	 */
	public function singletonResolved($key) {
		return array_key_exists($key, $this->instances);
	}


	/**
	 * Get an existing instance of a singleton
	 * @param  string $key Reference name of class
	 * @return mixed       The singleton instance or null
	 */
	public function getSingletonInstance($key) {
		return $this->singletonResolved($key) ? $this->instances[$key] : null;
	}


	/**
	 * Add a singleton to the instances array
	 * @param string $key   The reference name, must be unique
	 * @param object $value An existing class instance
	 * @return void
	 */
	public function addExistingSingleton($key, $value) {
		$namespace = get_class($value);
		$this->bindings[$key] = ['value' => $namespace, 'singleton' => true];
		$this->instances[$key] = $value;
	}


	/**
	 * Resolve a class with dependencies and arguements
	 * @param  string $key  The reference name of the class
	 * @param  array  $args Args to pass to the container
	 * @return mixed        The obeject and all dependencies or null
	 */
	public function resolve($key, array $args = []) {
		$class = $this->getBinding($key);

		if ($class === null) {
			$class = $key;
		}

		if ($this->isSingleton($key) && $this->singletonResolved($key)) {
			return $this->getSingletonInstance($key);
		}

		$object = $this->buildObject($class, $args);

		return $this->prepareObject($key, $object);
	}


	/**
	 * if the object is a singleton bind to instances
	 * @param  string $key    the refernece key
	 * @param  object $object The instance of the object to bind
	 * @return object         Returns the passed object instance
	 */
	protected function prepareObject($key, $object) {
		if ($this->isSingleton($key)) {
			$this->instances[$key] = $object;
		}

		return $object;
	}


	/**
	 * Loads the new instance of the object
	 * @param  string $class Name of the class to load
	 * @param  array  $args  Arguements to pass to the constructor
	 * @return object        New object with loaded dependencies
	 */
	protected function buildObject($class, array $args = []) {
		if (!isset($class['value'])) throw new DependencyDoesNotExistException('The Dependency being accessed does not exist');
		$className = $class['value'];
		$reflector = new ReflectionClass($className);


		if (!$reflector->isInstantiable()) {
			throw new ClassIsNotInstantiableException("Class {$className} is not a resolvable dependency");
		}

		if ($reflector->getConstructor() !== null) {
			$constructor = $reflector->getConstructor();
			$dependencies = $constructor->getParameters();

			$args = $this->buildDependencies($args, $dependencies, $class);
		}

		$object = $reflector->newInstanceArgs($args);

		return $object;
	}


	/**
	 * Recursively loads the class dependencies from the container
	 * @param  array  $args         Manually passed arguements
	 * @param  object $dependencies The dependencies from the constructor
	 * @param  string $class        The class name
	 * @return array                The arguements to pass
	 */
	protected function buildDependencies($args, $dependencies, $class) {
		$classArgs = [];

		foreach ($dependencies as $dependency) {
			if ($dependency->isOptional()) continue;
			if ($dependency->isArray()) continue;

			$class = $dependency->getClass();

			if ($class === null) continue;

			$name = $this->getKeyFromVal($class->name);

			$classArgs[] = $this->resolve($name);
		}

		$classArgs = array_reverse($classArgs);

		foreach ($classArgs as $classArg) {
			array_unshift($args, $classArg);
		}
		return $args;
	}

	public function getKeyFromVal($name) {
		foreach ($this->bindings as $key => $value) {
			if ($value['value'] == $name) {
				return $name = $key;
				break;
			}
		}
		throw new DependencyDoesNotExistException("Class {$name} is not a resolvable dependency");
	}

	// For accessing the $bindings array with array access.

	public function offsetGet($key) {
		return $this->resolve($key);
	}

	public function offsetSet($key, $value) {
		return $this->bind($key, $value);
	}

	public function offsetExists($key) {
		return array_key_exists($key, $this->bindings);
	}

	public function offsetUnset($key) {
		unset($this->bindings[$key]);
	}
}