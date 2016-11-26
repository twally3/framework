<?php

namespace App\Bootstrap\Facades;

use Framework\Core\Support\Facades\Facade;

Class QuxFacade extends Facade {
	protected static function getFacadeName() {
		return 'qux';
	}
}