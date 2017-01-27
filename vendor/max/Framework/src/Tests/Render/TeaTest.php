<?php

require_once '../../Core/Support/ServiceProviderInterface.php';
require_once '../../Core/Foundation/Container.php';
require_once '../../Core/Foundation/Application.php';
require_once '../../Core/Render/Tea.php';

Class TeaTest extends PHPUnit_framework_Testcase {

	public function setUp() {
		$app = $this->createMock(Framework\Core\Foundation\Application::class);

		$this->app = new Framework\Core\Render\Tea(
			$app
		);
	}

}