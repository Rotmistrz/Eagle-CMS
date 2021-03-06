<?php

class CategoriesList {
	private $categories = [];

	public function __construct($categories) {
		$this->categories = $categories;
	}

	public function add($category_id) {
		$this->categories[] = $category_id;
	}

	public static function createFromDatabaseFormat($string) {
		if(strlen($string) > 0) {
			return new self(explode(',', $string));
		} else {
			return new NoCategory();
		}
	}

	public function getDatabaseFormat() {
		return implode(',', $this->categories);
	}

	public function get() {
		return $this->categories;
	}

	public function getClassString() {
		$class = '';

		foreach($this->categories as $category_id) {
			$class .= "cat-" . $category_id;
		}

		return $class;
	}
}

?>