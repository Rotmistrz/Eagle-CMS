<?php

class ItemsCollection implements Viewable {
	private $items = [];
	private $type;
	private $i;

	const DEFAULT_TEMPLATE = 'default_items_';
	const TABLE_TEMPLATE = 'table_items_';

	public function __construct($type) {
		$this->type = $type;
		$this->i = 0;
	}

	public function addItem(Item $item) {
		$this->items[] = $item;
	}

	public function size() {
		return count($this->items);
	}

	public function decrement() {
		$this->i--;

		return $this->i;
	}

	public function increment() {
		$this->i++;

		return $this->i;
	}

	public function reset() {
		$this->i = 0;
	}

	public function get() {
		if($this->i >= $this->size()) {
			return false;
		}

		$current = $this->items[$this->i];
		$this->i++;

		return $current;
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
			$contents[$i]['type'] = $this->type;
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

	public function getDefault(Twig_Environment $twig, $lang) {
		return $twig->render(self::DEFAULT_TEMPLATE . $this->type . "." . TEMPLATE_EXTENSION, array('itemsCollection' => $this->getContentsByLanguage($lang)));
	}

	public function getTable(Twig_Environment $twig, $lang) {
		return $twig->render(self::TABLE_TEMPLATE . $this->type . "." . TEMPLATE_EXTENSION, array('itemsCollection' => $this->getContentsByLanguage($lang)));
	}

	public static function create($type) {
		$query = "SELECT * FROM " . ITEMS_TABLE . " WHERE type = :type";
		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':type', $type, PDO::PARAM_INT);
		$loading->execute();

		if($loading->rowCount() == 0) {
			return false;
		}

		$collection = new self($type);

		$fields = Item::getFields();
		$languages = Item::getLanguages();
		$languages_length = count($languages);
		$fields_length = count($fields);

		while($result = $loading->fetch()) {
			$id = $result['id'];
			$type = $result['type'];
			$category = $result['category'];
			$order = $result['sort'];
			$visible = $result['visible'];

			$item = new Item($id, $type, $order);

			for($i = 0; $i < $languages_length; $i++) {
				for($j = 0; $j < $fields_length; $j++) {
					$field = $fields[$j] . "_" . $languages[$i];
					if(isset($result[$field])) {
						$item->setContent($languages[$i], $fields[$j], $result[$field]);
					}
				}
			}

			$collection->addItem($item);
		}

		return $collection;
	}
}

?>