<?php

class CategoriesCollection {
	private $categories = [];

	public function __construct() {

	}

	public function add(Category $category) {
		$this->categories[] = $category;
	}

	public function create($type) {
		
	}
}

?>