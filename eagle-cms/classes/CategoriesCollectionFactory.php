<?php

class CategoriesCollectionFactory {
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
		$query = "SELECT * FROM " . CATEGORIES_TABLE . " WHERE type = :type ORDER BY sort ASC";
		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':type', $type, PDO::PARAM_INT);
		$loading->execute();

		if($loading->rowCount() == 0) {
			return new CategoriesCollection();
		}

		$collection = new CategoriesCollection();
		$fields = Category::getFields();
		$languages = Category::getLanguages();
		$languages_length = count($languages);
		$fields_length = count($fields);

		while($result = $loading->fetch()) {
			$id = $result['id'];
			$parent_id = $result['parent_id'];
			$type = $result['type'];
			$order = $result['sort'];

			$category = new Category($id, $type, $order);

			for($i = 0; $i < $languages_length; $i++) {
				for($j = 0; $j < $fields_length; $j++) {
					$field = $fields[$j] . "_" . $languages[$i];
					if(isset($result[$field])) {
						$category->setContent($languages[$i], $fields[$j], $result[$field]);
					}
				}
			}

			$collection->add($category);
		}

		return $collection;
	}
}

?>