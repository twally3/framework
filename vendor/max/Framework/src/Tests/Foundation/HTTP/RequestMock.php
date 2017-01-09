<?php

namespace Framework\Core\HTTP;

class Request {
	
}

class Router {
	public function submit() {
		$callback = function() {
			echo 'hey';
		};

		$params = [];
		$mid = [];
		return [$callback, $params, $mid];
	}
}