<?php

namespace Framework\Core\Support;

use Framework\Core\HTTP\Request;

interface MiddlewareInterface {

	public function handle(Request $request);

}