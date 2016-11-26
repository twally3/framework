<?php

namespace Framework\Core\Support\Facades;

use Framework\Core\Exceptions\FacadeDoesNotImplimentMethodException;

abstract class Facade {

	protected static $resolvedInstance;

	protected static $app;

	public static function setAppInstance($app) {
		static::$app = $app;
	} 

	public static function getFacadeRoot() {
		return static::resolveFacadeInstance(static::getFacadeName());
	}

	public static function resolveFacadeInstance($name) {
		if (is_object($name)) {
			return $name;
		}

		if (isset(static::$resolvedInstance[$name])) {
			return static::$resolvedInstance[$name];
		}

		// return static::$resolvedInstance[$name] = static::$app[$name];
		return static::$resolvedInstance[$name] = static::$app[$name];
	}

	protected static function getFacadeName() {
		throw new FacadeDoesNotImplimentMethodException('Facade does not impliment getFacadeAccessor method');
	}

	public static function __callStatic($method, $args) {
		$instance = static::getFacadeRoot();

		return $instance->$method(...$args);
	}
}