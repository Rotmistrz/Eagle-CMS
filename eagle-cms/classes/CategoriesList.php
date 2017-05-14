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
		return new self(explode(',', $string));
	}

	public function getDatabaseFormat() {
		return implode(',', $this->categories);
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