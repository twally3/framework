<?php

return [

	'providers' => [
		/*
		*	Framework Service Providers
		*/
		Framework\Core\Providers\TeaProvider::class,
		Framework\Core\Providers\SessionRequestProvider::class,
		Framework\Core\Providers\DatabaseProvider::class,
		Framework\Core\Providers\MigrationsProvider::class,
		Framework\Core\Providers\SchemaProvider::class,
		Framework\Core\Providers\TableProvider::class,
		Framework\Core\Providers\ValidatorProvider::class,
		Framework\Core\Providers\ORMProvider::class,
		Framework\Core\Providers\AuthenticationProvider::class,


		/*
		*	App Service Providers
		*/
		Framework\Core\Providers\RouterProvider::class,
		Framework\Core\Providers\RequestProvider::class,
		Framework\Core\Providers\FileRequestProvider::class,

		/*
		*	User Defined Service Providers
		*/
		App\Bootstrap\Providers\QuxProvider::class,

	],

	'aliases' => [
		'Route' => Framework\Core\Support\Facades\RouterFacade::class,
		'View' => Framework\Core\Support\Facades\TeaFacade::class,
		'Session' => Framework\Core\Support\Facades\SessionRequestFacade::class,
		'Database' => Framework\Core\Support\Facades\DatabaseFacade::class,
		'Schema' => Framework\Core\Support\Facades\SchemaFacade::class,
		'Validate' => Framework\Core\Support\Facades\ValidatorFacade::class,
		'ORM' => Framework\Core\Support\Facades\ORMFacade::class,
		'Auth' => Framework\Core\Support\Facades\AuthenticationFacade::class,
		'Qux' => App\Bootstrap\Facades\QuxFacade::class
	]


];