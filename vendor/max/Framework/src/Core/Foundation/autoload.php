<?php

namespace Framework\Core\Foundation;

Class Autoload {

	/**
	 * The base path of the application
	 * @var string
	 */
	protected $basepath;


	/**
	 * store the basepath, setup the autoloader and load the files
	 */
	public function __construct() {
		spl_autoload_register([$this, 'loader']);
		$this->basepath = realpath(__DIR__ . '/../../../../../../');
		
		$loadjson = file_get_contents($this->basepath . '/vendor/autoload.json');
		$this->json = json_decode($loadjson);
		
		$this->loadFiles();
	}


	/**
	 * The autoload method
	 * @param  string $classname The name of the class being loaded passed by spl_autoload
	 * @return void
	 */
	public function loader($classname) {
		// echo $classname . '<br>';
		$namearray = explode('\\', $classname);

		$key = $namearray[0] . '\\';
		// debugArray($namearray);
		if (isset($this->json->autoload->namespace->$key)) {
			$value = $this->json->autoload->namespace->$key;

			unset($namearray[0]);
			$path = implode('/', $namearray);

			require $this->basepath . $value . '/' . $path . '.php';
			return;
		} else {
			$path = implode('/', $namearray);
			require $this->basepath . '/' . $path . '.php';
			return;
		}
		throw new \Exception("Autoloader cannot load class {$classname}");
	}


	/**
	 * Load induvidual files
	 * @return void
	 */
	public function loadFiles() {
		foreach ($this->json->autoload->files as $file) {
			// echo "$file";
			require $this->basepath . $file;
		}
	}
}

$x = new Autoload;