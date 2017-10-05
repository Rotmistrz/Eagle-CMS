<?php

class FileUploader {
	public $path;
	private $files;
	private $errors;

	public function __construct($path) {
		$this->files = [];
		$this->errors = [];

		$this->path = $path;
	}

	public function addFile($fieldname, $path, $type, $maxsize) {
		$this->files[] = ['fieldname' => $fieldname, 'path' => $path, 'type' => $type, 'maxsize' => $maxsize];
	}

	public function getErrors() {
		return $this->errors;
	}

	public function clear() {
		$this->errors = [];
	}

	public function upload() {
		$this->clear();

		for($i = 0; $i < count($this->files); $i++) {
			if(isset($_FILES[$this->files[$i]['fieldname']])) {
				$fieldname = $this->files[$i]['fieldname'];

				if(!file_exists($_FILES[$fieldname]['tmp_name']) || !is_uploaded_file($_FILES[$fieldname]['tmp_name'])) {
				    continue;
				}

				$fileName = $_FILES[$fieldname]['name'];
				$fileSize = $_FILES[$fieldname]['size'];
				$fileTmp = $_FILES[$fieldname]['tmp_name'];
				$fileType = $_FILES[$fieldname]['type'];
				
				$array = explode('.', $fileName);
                $last = count($array) - 1;

                $fileExt = strtolower($array[$last]);

				if($fileExt != $this->files[$i]['type']) {
					$this->errors[] = $fileName . ": Niepoprawny format pliku";
				}

				if($fileSize > $this->files[$i]['maxsize']) {
					$this->errors[] = $fileName . ": Zbyt duży rozmiar pliku";
				}

				if(count($this->errors) == 0) {
					if(!move_uploaded_file($fileTmp, $this->path . $this->files[$i]['path'] . '.' . $fileExt)) {
						$this->errors[] = $fileName . ": problem podczas przenoszenia pliku";
					}
				}
		   }
		}

		if(count($this->errors) == 0) {
			return true;
		} else {
			return false;
		}
	}
}

?>