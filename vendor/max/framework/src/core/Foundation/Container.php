<?php

namespace Framework\Core\Foundation;

use ReflectionClass;
use ArrayAccess;
use Framework\Core\Exceptions\DependencyNameAlreadyInUseException;
use Framework\Core\Exceptions\ClassIsNotInstantiableException;
use Framework\Core\Exceptions\DependencyDoesNotExistException;

class Container implements ArrayAccess {
	protected $bindings = [];
	protected $instances = [];

	public function bind($key, $value, $singleton = false) {
		if (!is_null($this->getBinding($key))) throw new DependencyNameAlreadyInUseException('Dependency Name is already in use');

		$this->bindings[$key] = compact('value', 'singleton');
	}

	public function singleton($key, $value) {
		return $this->bind($key, $value, true);
	}

	public function getBinding($key) {
		if (!array_key_exists($key, $this->bindings)) {
			return null;
		}

		return $this->bindings[$key];
	}

	public function isSingleton($key) {
		$binding = $this->getBinding($key);

		if ($binding === null) {
			return false;
		}

		return $binding['singleton'];
	}

	public function singletonResolved($key) {
		return array_key_exists($key, $this->instances);
	}

	public function getSingletonInstance($key) {
		return $this->singletonResolved($key) ? $this->instances[$key] : null;
	}

	public function addExistingSingleton($key, $value) {
		$namespace = get_class($value);
		$this->bindings[$key] = ['value' => $namespace, 'singleton' => true];
		$this->instances[$key] = $value;
	}

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

	protected function prepareObject($key, $object) {
		if ($this->isSingleton($key)) {
			$this->instances[$key] = $object;
		}

		return $object;
	}

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

	public function buildDependencies($args, $dependencies, $class) {
		$classArgs = [];

		foreach ($dependencies as $dependency) {
			if ($dependency->isOptional()) continue;
			if ($dependency->isArray()) continue;

			$class = $dependency->getClass();

			if ($class === null) continue;

			// if (get_class($this) === $class->name) {
			// 	array_unshift($args, $this);
			// 	continue;
			// }

			$name = explode('\\', $class->name);
			$name = strtolower(array_pop($name));

			$classArgs[] = $this->resolve(strtolower($name));
		}

		$classArgs = array_reverse($classArgs);

		foreach ($classArgs as $classArg) {
			array_unshift($args, $classArg);
		}
		return $args;
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