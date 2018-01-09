<?php

class ItemsCollection implements LanguagableCollection {
	private $items = [];

	public function __construct() {

	}

	public function add(Item $item) {
		$this->items[] = $item;

		return $this;
	}

	public function get($i) {
		if(array_key_exists($i, $this->items)) {
			return $this->items[$i];
		} else {
			return false;
		}
	}

	public function size() {
		return count($this->items);
	}

	public function getItems() {
		return $this->items;
	}

	public function getContentsByLanguage($lang) {
		$contents = [];

		foreach($this->items as $item) {
			$contents[] = $item->getContentsByLanguage($lang);
		}

		return $contents;
	}
}

?>