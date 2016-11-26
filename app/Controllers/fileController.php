<?php

use Framework\Core\HTTP\Controller;
use Framework\Core\HTTP\Request;

Class fileController extends Controller {
	public function index() {
		View::make('files/files');
	}

	public function recieve(Request $request) {
		if ($request->hasFile('file')) {
			$files = $request->file('file');

			$file->getOriginalName();
			$file->store('files', $file->getOriginalName());
			$file->fileSizeGreaterThan('1771568', true);
			$file->isValid();
		}
	}

	public function recieveOne(Request $request) {
		$file = $request->file('file');

		// echo $file->hasExtention(['png', 'jpg', 'jpeg']);

		// echo $file->uniqueName('files');

		// echo $file->getName();

		echo $file->nameExists('files', $file->getName());

		echo $file->getName();


		$file->store('files', $file->getName());
		View::make('files/recieveOne', ['file' => $file]);
	}

	public function recieveMany(Request $request) {
		if ($request->hasFile('files')) {
			$files = $request->file('files');

			foreach ($files as $file) {
				$file->store('files', $file->uniqueName('files'));
			}
			View::make('files/recieveMany', ['files' => $files]);
		} else {
			throw new Exception('No files given!');
		}
	} 
}