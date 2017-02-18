<?php

class Contents {
	private $contents = [];

	public function __construct() {

	}

	public function set($lang, $field, $value) {
		$this->contents[$lang][$field] = $value;
	}

	public function get($lang, $field) {
		if(array_key_exists($lang, $this->contents) && array_key_exists($field, $this->contents[$lang])) {
			return $this->contents[$lang][$field];
		} else {
			return '';
		}
	}

	public function getContentsByLanguage($lang) {
		if(array_key_exists($lang, $this->contents)) {
			return $this->contents[$lang];
		} else {
			return false;
		}
	}

	public function getLanguages() {
		$languages = [];
		
		foreach($this->contents as $language => $values) {
			$languages[] = $language;
		}

		return $languages;
	}
}

?>