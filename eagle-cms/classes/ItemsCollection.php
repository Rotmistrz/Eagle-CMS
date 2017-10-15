<?php

class ItemsCollection implements LanguagableCollection {
	private $items = [];

	public function __construct() {

	}

	public function add(Item $item) {
		$this->items[] = $item;
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

	public static function load($type) {
		$query = "SELECT * FROM " . ITEMS_TABLE . " WHERE type = :type ORDER BY sort ASC";
		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':type', $type, PDO::PARAM_INT);
		$loading->execute();

		if($loading->rowCount() == 0) {
			return new self();
		}

		$collection = new self();

		$fields = Item::getFields();
		$languages = Item::getLanguages();
		$languages_length = count($languages);
		$fields_length = count($fields);

		while($result = $loading->fetch()) {
			$item = Item::createFromDatabaseRow($result);

			$collection->add($item);
		}

		return $collection;
	}

	public static function loadByCategory($type) {
		$caseA = $type;
		$caseB = $type . ",%";
		$caseC = "%," . $type;
		$caseD = "%," . $type . ",%";

		$query = "SELECT * FROM " . ITEMS_TABLE . " WHERE category LIKE :caseA
													   OR category LIKE :caseB
													   OR category LIKE :caseC 
													   OR category LIKE :caseD ORDER BY sort ASC";

		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':caseA', $caseA, PDO::PARAM_STR);
		$loading->bindValue(':caseB', $caseB, PDO::PARAM_STR);
		$loading->bindValue(':caseC', $caseC, PDO::PARAM_STR);
		$loading->bindValue(':caseD', $caseD, PDO::PARAM_STR);
		$loading->execute();

		if($loading->rowCount() == 0) {
			return new self();
		}

		$collection = new self();

		$fields = Item::getFields();
		$languages = Item::getLanguages();
		$languages_length = count($languages);
		$fields_length = count($fields);

		while($result = $loading->fetch()) {
			$item = Item::createFromDatabaseRow($result);

			$collection->add($item);
		}

		return $collection;
	}
}

?>