<?php

namespace Framework\Core\Foundation;

Class Autoload {

	protected $basepath;

	public function __construct() {
		spl_autoload_register([$this, 'loader']);
		$this->basepath = realpath(__DIR__ . '/../../../../../../');
		
		$loadjson = file_get_contents($this->basepath . '/vendor/autoload.json');
		$this->json = json_decode($loadjson);
		
		$this->loadFiles();
	}

	public function loader($classname) {
		// echo $classname . '<br>';
		$namearray = explode('\\', $classname);

		$key = $namearray[0] . '\\';

		if (isset($this->json->autoload->namespace->$key)) {
			$value = $this->json->autoload->namespace->$key;
			// echo $value;
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

	public function loadFiles() {
		foreach ($this->json->autoload->files as $file) {
			require $this->basepath . $file;
		}
	}
}

$x = new Autoload;