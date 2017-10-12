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

	/**
	 *
	 * $fieldname String
	 * $path String
	 * $type array[String - mime-type]
	 * $maxsize Integer
	 *
	 **/
	public function addFile($fieldname, $path, $type, $maxsize, $required = false) {
		$this->files[] = ['fieldname' => $fieldname, 'path' => $path, 'type' => $type, 'maxsize' => $maxsize, 'required' => $required];

		if(isset($_FILES[$fieldname]) && in_array($_FILES[$fieldname]['type'], $type)) {
			return $_FILES[$fieldname]['type'];
		} else {
			return false;
		}
	}

	public function getErrors() {
		return $this->errors;
	}

	public function getErrorsAsString() {
		$str = "";

		foreach($this->errors as $error) {
			$str .= $error . "<br />";
		}

		return $str;
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

				if(!in_array($fileType, $this->files[$i]['type'])) {
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
		   } else if($this->files[$i]['required']) {
		   		$this->errors[] = $this->files[$i]['filename'] . ": nie załączono pliku";
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