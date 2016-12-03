<?php

namespace Framework\Core\Support\Facades;

use Framework\Core\Support\Facades\Facade;

Class JWTFacade extends Facade {
	protected static function getFacadeName() {
		// Return the container reference for the class!
		return 'jwt';
	}
}