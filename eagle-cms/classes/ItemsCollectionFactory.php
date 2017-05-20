<?php

class ItemsCollectionFactory {
	public $loadHiddenItems = false; // boolean
	private $orderType;

	public function __construct($loadHiddenItems = false) {
		$this->loadHiddenItems = $loadHiddenItems;
		$this->orderType = Order::ASC;
	}

	public function setDescendingOrder() {
		$this->orderType = Order::DESC;
	}

	public function setAscendingOrder() {
		$this->orderType = self::ASC;
	}

	public function load($type) {
		$query = "SELECT * FROM " . ITEMS_TABLE . " WHERE type = :type";

		if(!$this->loadHiddenItems) {
			$query .= " AND visible != 0";
		}

		$query .= " ORDER BY sort " . $this->orderType;

		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':type', $type, PDO::PARAM_INT);
		$loading->execute();

		$collection = new ItemsCollection();

		if($loading->rowCount() == 0) {
			return $collection;
		}

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

	public function loadByCategory($type) {
		$caseA = $type;
		$caseB = $type . ",%";
		$caseC = "%," . $type;
		$caseD = "%," . $type . ",%";

		$query = "SELECT * FROM " . ITEMS_TABLE . " WHERE category LIKE :caseA
													   OR category LIKE :caseB
													   OR category LIKE :caseC 
													   OR category LIKE :caseD";

		if(!$this->loadHiddenItems) {
			$query .= " AND visible != 0";
		}

		$query .= " ORDER BY sort " . $this->orderType;

		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':caseA', $caseA, PDO::PARAM_STR);
		$loading->bindValue(':caseB', $caseB, PDO::PARAM_STR);
		$loading->bindValue(':caseC', $caseC, PDO::PARAM_STR);
		$loading->bindValue(':caseD', $caseD, PDO::PARAM_STR);
		$loading->execute();

		if($loading->rowCount() == 0) {
			return new ItemsCollection();
		}

		$collection = new ItemsCollection();

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