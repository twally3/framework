<?php

class Autoload {
	public $classes = [];
	public $dirs = [];
	public $xdirs = [];

	public function load() {
		$this->getDir();

		foreach ($this->dirs as $dirs) {
			$this->getClasses(realpath(__DIR__ . '/../../../../../..' . $dirs));
		}

		// echo '<pre>';
		// print_r($this->classes);
		// die;

		$this->loadClasses();
		
		// if (php_sapi_name() == 'cli') {
		// 	$this->makeAutoload();
		// } else {
		// 	$this->loadClasses();
		// }
	}

	public function getDir() {
		$x = json_decode(file_get_contents(__DIR__ . '/../../../../../autoload.json'));
		$this->dirs = $x->autoload->dir ?: [];
		$this->xdirs = $x->autoload->xdir ?: [];

		if (!empty($this->xdirs)) {
			array_walk($this->xdirs, function(&$item1, $key) {
				$item1 = strtolower(realpath(__DIR__ . '/../../../../../..' . $item1));
			});
		}

		$this->classes = $x->autoload->files ?: [];

		if (!empty($this->classes)) {
			array_walk($this->classes, function(&$item1, $key) {
				$item1 = strtolower(realpath(__DIR__ . '/../../../../../..' . $item1));
			});
		}

		$this->xfiles = $x->autoload->xfiles ?: [];

		if (!empty($this->xfiles)) {
			array_walk($this->xfiles, function(&$item1, $key) {
				$item1 = strtolower(realpath(__DIR__ . '/../../../../../..' . $item1));
			});
		}

	}

	public function loadClasses() {
		foreach ($this->classes as $classes) {
			require_once $classes;
		}
	}

	public function makeAutoload() {
		$txt = '<?php ';
		$loader = fopen(__DIR__ . "/../../../../autoload.php \n", "w");

		foreach ($this->classes as $classes) {
			$txt .= "require_once {$classes};";
		}

	    fwrite($loader, $txt);
	    fclose($loader);
	}

	public function getClasses($dir) {
		$files = scandir($dir);

		unset($files[0]);
		unset($files[1]);
		$files = array_values($files);

		foreach ($files as $file) {

			$fileArray = explode('.', $file);

			if (count($fileArray) > 1) {
				// is a file
				// echo strtolower($dir . '/' . $file);
				// echo '<pre>';
				// print_r($this->classes);
				// echo '<hr>';
				if (in_array(strtolower($dir . '/' . $file), $this->xfiles)) return;
				if (in_array(strtolower($dir . '/' . $file), $this->classes)) return;

				$this->classes[] = strtolower($dir . '/' . $file);
			} else {
				// is a folder
				if (in_array($dir . '/' . $file, $this->xdirs)) return;
				$this->getClasses($dir . '/' . $file);
			}
		}
	}
}

return new Autoload;