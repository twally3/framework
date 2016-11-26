<?php

namespace Framework\Core\Foundation;

use Framework\Core\Support\ServiceProviderInterface;
use \Exception;

Class Application extends Container {
	protected $loadedProviders = [];
	public $app;

	public function __construct($basepath = null) {
		set_exception_handler([$this, 'exception']);
		set_error_handler([$this, 'error']);

		if (!is_null($basepath)) {
			$this->basepath = $basepath;
		}
		
		$this->app = require __DIR__ . '/../../../../../../app/config/app.php';

		$this->baseBindings();
		$this->loadProviders();
		$this->addFacades();
		

		$this->bootProviders();

	}

	public function loadProviders() {
		$app = $this->app;

		foreach ($app['providers'] as $provider) {
			$this->registerProvider(new $provider);
		}
	}

	public function baseBindings() {
		// Add current app instance to the container
		$this->addExistingSingleton('application', $this);
	}

	public function addFacades() {
		$app = $this->app;

		foreach ($app['aliases'] as $key => $alias) {
			class_alias($alias, $key);
			$alias::setAppInstance($this);
		}
	}

	public function bootProviders() {
		$app = $this->app;

		foreach ($this->loadedProviders as $provider) {
			if (method_exists($provider, 'boot')) {
				$provider->boot();
			}
		}
	}

	public function registerProvider(ServiceProviderInterface $provider) {
		if (!$this->providerHasBeenLoaded($provider)) {
			$provider->register($this);

			// $this->loadedProviders[] = get_class($provider);
			$this->loadedProviders[get_class($provider)] = $provider;
		}

		return $this;
	}

	public function providerHasBeenLoaded(ServiceProviderInterface $provider) {
		return array_key_exists(get_class($provider), $this->loadedProviders);
	}

	public function exception($e) {
		$last_error = error_get_last();
	  if ($last_error['type'] === E_ERROR) {
	    // fatal error
	    error(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
	  } else {
			$message = $e->getMessage();
			$line = $e->getLine();
			$file = $e->getFile();
			include __DIR__ . '/../render/error.php';
			echo "<h3>";
			echo $message . '<span class="line"> on line ' . $line . '</span>';
			echo '<br><span class="file">' . $file . '</span>';
			echo "</h3>";
			echo $this->getStackTrace($e);
	  }
	}

	public function error($num, $msg, $file, $line) {
		$error = $msg. '<br>File: '. $file. '<br>Line: '. $line;
		throw new \Exception($error);
	}

	public function getStackTrace($e) {
		$trace = explode("\n", $e->getTraceAsString());
	    // reverse array to make steps line up chronologically
	    $trace = array_reverse($trace);
	    array_shift($trace); // remove {main}
	    array_pop($trace); // remove call to this method
	    $length = count($trace);
	    $result = array();
	    
	    for ($i = 0; $i < $length; $i++) {
	        // $result[] = substr($trace[$i], strpos($trace[$i], ' ')); // replace '#someNum' with '$i)', set the right ordering
	        $str = substr($trace[$i], strpos($trace[$i], ' '));
	        preg_match("#\((.*?)\):#", $str, $line);
	        preg_match("#\/(.*?)\.php#", $str, $file);
	        $name = explode('\\', $str);
	        $name = array_pop($name);
	        $file = explode('/', $file[1]);
	        $file = array_pop($file) . '.php';

	        $final = $name . '<span class="line"> in ' . $file . ' line ' . $line[1] . '</span>';
	        $result[] = $final;
	        // debugArray($final);
	    }

	    // die;
	    
	    return $final = "<ol><li>" . implode("</li><li>", $result) . '</li>';
    // preg_replace()
	}
}