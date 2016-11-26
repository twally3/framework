<?php

namespace Framework\Core\Support;

use Framework\Core\Foundation\Application;

interface ServiceProviderInterface {
	public function register(Application $app);
}