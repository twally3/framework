<?php 

namespace Framework\Core\HTTP;

Class FileRequest {


	/**
	 * Setup the file data
	 * @param array $file file POST data
	 */
	function __construct($file=null, $testing=false) {
		$this->file = $file;

		$this->originalName = $file['name'];
		$this->fileTmp = $file['tmp_name'];
		$this->fileSize = $file['size'];
		$this->fileError = $file['error'];
		$this->fileType = $file['type'];

		$this->fileLocation = $this->fileTmp;
		$this->fileName = $this->originalName;

		$this->fileExt = explode('.', $this->fileName);
		$this->fileExt = strtolower(end($this->fileExt));

		if (in_array($this->fileExt, ['jpeg', 'jpg']) && !$testing) {
			$this->rotation = $this->rotation($this->fileLocation);
		}

		return $this;
	}


	/**
	 * Gets rotation data of degrees of a file in $location
	 * @param  string $location The location of the image
	 * @return integer          The image rotation in degrees
	 */
	function rotation($location) { 
		$exif = exif_read_data($location, 0, true);
		if (isset($exif['IFD0']['Orientation'])) {
			switch($exif['IFD0']['Orientation']) {
				case '1':
					return 0;
					break;
				case '8':
					return 270;
					break;
				case '3':
					return 180;
					break;
				case '6':
					return 90;
					break;
				default:
					return 0;
			}
		} return 0;
	}

	/**
	 * Gets the currently set name of the file
	 * @return string Current file name
	 */
	function getName() {
		return $this->fileName;
	}

	/**
	 * Gets the original upload name of the file
	 * @return string The original file name
	 */
	function getOriginalName() {
		return $this->originalName;
	}

	/**
	 * Checks the file uploaded with no errors
	 * @return boolean Has the file experienced an error
	 */
	function isValid() {
		return $this->fileError == 0 ? true : false;
	}

	/**
	 * Stores the file at the $location with the specified $name (name includes extention). Root is at /public.
	 * @param  string $location The location to store the file
	 * @param  string $name     The name.ext of the file
	 * @return boolean          Success
	 */
	function store($location, $name) {
		if(move_uploaded_file($this->fileLocation, "{$location}/{$name}")) {
			$this->fileLocation = "./{$location}/{$name}";
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Returns true if the file size is less than $size.
	 * @param  integer $size The size the file should equal
	 * @return boolean       Success
	 */
	function fileSizeEqualTo($size) {
		return ($this->fileSize == $size) ? true : false;
	}

	/**
	 * Returns true if the file size is less than $size. Uses >= if Param[2] is set to true
	 * @param  integer $size    target size
	 * @param  boolean $equalTo can be equal to
	 * @return boolean          Success
	 */
	function fileSizeGreaterThan($size, $equalTo=false) {
		if ($equalTo) {
			return ($this->fileSize >= $size) ? true : false;
		} else {
			return ($this->fileSize > $size) ? true : false; 
		}
	}

	/**
	 * Returns true if the file size is less than $size. Uses <= if Param[2] is set to true
	 * @param  integer  $size    target size
	 * @param  boolean $equalTo  can be equal to
	 * @return boolean           Success
	 */
	function fileSizeLessThan($size, $equalTo=false) {
		if ($equalTo) {
			return ($this->fileSize <= $size) ? true : false;
		} else {
			return ($this->fileSize < $size) ? true : false; 
		}
	}

	/**
	 * Checks if a file has an extention in an array or param list
	 * @return boolean Has the file got a give extention
	 */
	function hasExtention() {
		$argCount = func_num_args();
		$args = func_get_args();
		$results = [];

		if ($argCount == 1 && is_array($args[0])) {
			$results = $args[0];
		} else if($argCount >= 1 && !is_array($args[0])) {
			foreach ($args as $arg) {
				$results[] = $arg;
			}
		}
		return (in_array($this->fileExt, $results));
	}

	/**
	 * Returns a random name of length specified
	 * @param  integer $length Length of the new name
	 * @return string          The new name of the file
	 */
	function randomName($length) {
    $key = '';
    $keys = array_merge(range(0, 9), range('a', 'z'));

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }

    return "{$key}.{$this->fileExt}";
	}

	/**
	 * Generate a unique name for a file at path specified
	 * @param  string $path the path to create the file
	 * @return string       The new unique file name
	 */
	function uniqueName($path) {
		$filename = tempnam("{$path}", '');
		unlink($filename);
		$nameArray = explode('/', $filename . ".{$this->fileExt}");
		return $this->fileName = array_pop($nameArray);
	}

	/**
	 * Checking if a user defined name exists
	 * @param  string $path The path of the file
	 * @param  string $name The name of the file
	 * @return boolean      Does the file exist?
	 */
	function nameExists($path, $name) {
		return file_exists("../public/{$path}/{$name}");
	}
}