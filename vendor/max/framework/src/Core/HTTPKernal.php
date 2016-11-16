<?php namespace Framework\Core;

Class HTTPKernal {

	public static function getProp($name) {
		$class = get_called_class();
		return $class::$$name;
	}
}