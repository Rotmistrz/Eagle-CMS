<?php

class ItemsCollectionFactory {
	public $after = -1;
	public $limit = -1;

	public $loadHiddenItems = false; // boolean
	private $orderType;

	public function __construct($loadHiddenItems = false) {
		$this->loadHiddenItems = $loadHiddenItems;
		$this->orderType = Order::ASC;
	}

	public function setDescendingOrder() {
		$this->orderType = Order::DESC;

		return $this;
	}

	public function setAscendingOrder() {
		$this->orderType = Order::ASC;

		return $this;
	}

	public function load($type) {
		$query = "SELECT * FROM " . ITEMS_TABLE . " WHERE type = :type";

		if(!$this->loadHiddenItems) {
			$query .= " AND visible != 0";
		}

		$query .= " ORDER BY sort " . $this->orderType;

		if($this->after > -1 && $this->limit > -1) {
			$query .= " LIMIT " . $this->after . ", " . $this->limit;
		} else if($this->limit > -1) {
			$query .= " LIMIT " . $this->limit;
		}

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

		$query = "SELECT * FROM " . ITEMS_TABLE . " WHERE ";

		if(!$this->loadHiddenItems) {
			$query .= "visible != 0 AND ";
		}

		$query .= "(category LIKE :caseA
			    OR category LIKE :caseB
			    OR category LIKE :caseC 
			    OR category LIKE :caseD)";

		$query .= " ORDER BY sort " . $this->orderType;

		if($this->after > -1 && $this->limit > -1) {
			$query .= " LIMIT " . $this->after . ", " . $this->limit;
		} else if($this->limit > -1) {
			$query .= " LIMIT " . $this->limit;
		}

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

	public function loadByParent($parent, $type) {
		$query = "SELECT * FROM " . ITEMS_TABLE . " WHERE type = :type AND parent_id = :parent_id";

		if(!$this->loadHiddenItems) {
			$query .= " AND visible != 0";
		}

		$query .= " ORDER BY sort " . $this->orderType;

		if($this->after > -1 && $this->limit > -1) {
			$query .= " LIMIT " . $this->after . ", " . $this->limit;
		} else if($this->limit > -1) {
			$query .= " LIMIT " . $this->limit;
		}
		
		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':type', $type, PDO::PARAM_INT);
		$loading->bindValue(':parent_id', $parent, PDO::PARAM_INT);
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
}

?>