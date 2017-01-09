<?php

namespace Framework\Core\Foundation;

use Framework\Core\Support\ServiceProviderInterface;
use \Exception;

Class Application extends Container {

	/**
	 * Array of all loaded providers
	 * @var array
	 */
	protected $loadedProviders = [];

	/**
	 * The app config array
	 * @var array
	 */
	public $app;


	/**
	 * Sets up the application
	 * @param String $basepath The base path of the application
	 */
	public function __construct($basepath = null, $appconf = null) {
		// set_exception_handler([$this, 'exception']);
		// set_error_handler([$this, 'error']);

		if (!is_null($basepath)) {
			$this->basepath = $basepath;
		}
		
		if (!is_null($appconf)) {
			$this->app = $appconf;
		} else {
			$this->app = require __DIR__ . '/../../../../../../App/Config/app.php';
		}

		$this->baseBindings();
		$this->loadProviders();
		$this->addFacades();
		

		$this->bootProviders();

	}


	/**
	 * Loads all the registered providers
	 * @return void
	 */
	protected function loadProviders() {
		$app = $this->app;

		foreach ($app['providers'] as $provider) {
			$this->registerProvider(new $provider);
		}
	}


	/**
	 * Add the current app instance to the container
	 * @return void
	 */
	protected function baseBindings() {
		// Add current app instance to the container
		$this->addExistingSingleton('application', $this);
	}


	/**
	 * Load all registered facades
	 * @return void
	 */
	protected function addFacades() {
		$app = $this->app;

		foreach ($app['aliases'] as $key => $alias) {
			class_alias($alias, $key);
			$alias::setAppInstance($this);
		}
	}


	/**
	 * Runs the boot method on the loaded providers
	 * @return void
	 */
	protected function bootProviders() {
		$app = $this->app;

		foreach ($this->loadedProviders as $provider) {
			if (method_exists($provider, 'boot')) {
				$provider->boot();
			}
		}
	}


	/**
	 * Register the service provider
	 * @param  ServiceProviderInterface $provider The provider to be loaded
	 * @return $this                              Current instance of the app
	 */
	public function registerProvider(ServiceProviderInterface $provider) {
		if (!$this->providerHasBeenLoaded($provider)) {
			$provider->register($this);

			// $this->loadedProviders[] = get_class($provider);
			$this->loadedProviders[get_class($provider)] = $provider;
		}

		return $this;
	}


	/**
	 * Checks if a provider has been loaded
	 * @param  ServiceProviderInterface $provider Target provider
	 * @return Boolean                            Success
	 */
	public function providerHasBeenLoaded(ServiceProviderInterface $provider) {
		return array_key_exists(get_class($provider), $this->loadedProviders);
	}
}