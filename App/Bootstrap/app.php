<?php

$app = new Framework\Core\Foundation\Application(
	realpath(__DIR__ . '/../../')
);

$app->singleton('httpkernel', 'App\HTTP\Kernel');

$app->singleton('clikernel', 'Framework\Core\Foundation\Console\CliKernel');

return $app;
