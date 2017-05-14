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
		$current;
		$i = 0;
		$contents = [];

		foreach($this->items as $item) {
			$contents[$i]['id'] = $item->getId();
			$contents[$i]['type'] = $item->type;
			$contents[$i]['parent_id'] = $item->parentId;
			$contents[$i]['order'] = $item->order;
			$contents[$i]['visible'] = $item->visible;

			if($current = $item->getContentsByLanguage($lang)) {
				$contents[$i] = array_merge($contents[$i], $current);
			}

			$i++;
		}

		return $contents;
	}

	public static function create($type) {
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
}

?>