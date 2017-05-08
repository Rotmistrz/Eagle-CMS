<?php

class Item {
	public $id;
	public $type;
	public $parentId;
	private $category;
	public $order;
	public $visible;

	const HEADER_1 = 'header_1';
	const HEADER_2 = 'header_2';
	const HEADER_3 = 'header_3';
	const HEADER_4 = 'header_4';
	const HEADER_5 = 'header_5';

	const CONTENT_1 = 'content_1';
	const CONTENT_2 = 'content_2';
	const CONTENT_3 = 'content_3';

	private static $fields = [self::HEADER_1, self::HEADER_2, self::HEADER_3, self::HEADER_4, self::HEADER_5,
	                   self::CONTENT_1, self::CONTENT_2, self::CONTENT_3];

	private static $languages = [Language::PL, Language::EN];

	private $contents;

	public function __construct($id, $type, $order) {
		$this->id = $id;
		$this->type = $type;
		$this->order = $order;
		$this->category = null;
		$this->visible = 1;
		$this->parentId = 0;

		$this->contents = new Contents();
	}

	public function setVisible($visible) {
		if(is_bool($visible)) {
			$this->visible = (bool) $visible;
		} else {
			throw new Exception("Item::setVisible() must receive boolean value.");
		}
	}

	public function getVisible() {
		return $this->visible;
	}

	public function setCategory(Category $category) {
		$this->category = $category;
	}

	public function setOrder($order) {
		if(is_number($order)) {
			$this->order = $order;
		} else {
			throw new Exception("Item::setOrder() must receive integer value.");
		}
	}

	public function setContent($lang, $field, $value) {
		$this->contents->set($lang, $field, $value);
	}

	public function getContent($lang, $field) {
		return $this->contents->get($lang, $field);
	}

	public function getContentsByLanguage($lang) {
		return $this->contents->getContentsByLanguage($lang);
	}

	public function save() {
		$fields = self::$fields;
		$languages = self::$languages;
		$languages_length = count($languages);
		$fields_length = count($fields);
		$current;
		$category_id;

		$query = '';

		$pdo = DataBase::getInstance();

		$query .= "UPDATE " . ITEMS_TABLE . " SET type = :type, category = :category";

		for($i = 0; $i < $languages_length; $i++) {
			for($j = 0; $j < $fields_length; $j++) {
				$current = self::getDatabaseFieldname($fields[$j], $languages[$i]);
				$query .= ", " . $current . " = :" . $current;
			}
		}

		$query .= " WHERE id = :id";

		if(!is_null($this->category)) {
			$category_id = $this->category->getId();
		} else {
			$category_id = 0;
		}

		$loading = $pdo->prepare($query);
		$loading->bindValue(':id', $this->id, PDO::PARAM_INT);
		$loading->bindValue(':type', $this->type, PDO::PARAM_INT);
		$loading->bindValue(':category', $category_id, PDO::PARAM_INT);

		for($i = 0; $i < $languages_length; $i++) {
			for($j = 0; $j < $fields_length; $j++) {
				$current = $fields[$j] . "_" . $languages[$i];

				$loading->bindValue(':' . $current, $this->contents->get($languages[$i], $fields[$j]), PDO::PARAM_STR);
			}
		}

		if($loading->execute()) {
			return true;
		} else {
			return false;
		}
	}

	public function delete() {
		$query = "DELETE FROM " . ITEMS_TABLE . " WHERE id = :id";

		$pdo = DataBase::getInstance();
		$deleting = $pdo->prepare($query);
		$deleting->bindValue(':id', $this->id, PDO::PARAM_INT);

		if($deleting->execute()) {
			return true;
		} else {
			return false;
		}
	}

	public function getEarlierOne() {
		$query = "SELECT * FROM " . ITEMS_TABLE . " WHERE type = :type AND sort < :sort ORDER BY sort DESC";

		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':type', $this->type, PDO::PARAM_INT);
		$loading->bindValue(':sort', $this->order, PDO::PARAM_INT);
		$loading->execute();

		if($row = $loading->fetch()) {
			return self::createFromDatabaseRow($row);
		} else {
			return new NoItem();
		}
	}

	public function getLaterOne() {
		$query = "SELECT * FROM " . ITEMS_TABLE . " WHERE type = :type AND sort < :sort ORDER BY sort DESC";

		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':type', $this->type, PDO::PARAM_INT);
		$loading->bindValue(':sort', $this->order, PDO::PARAM_INT);
		$loading->execute();

		if($row = $loading->fetch()) {
			return self::createFromDatabaseRow($row);
		} else {
			return new NoItem();
		}
	}

	public static function load($id) {
		$query = "SELECT * FROM " . ITEMS_TABLE . " WHERE id = :id";

		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':id', $id, PDO::PARAM_INT);

		if($loading->execute()) {
			$result = $loading->fetch();

			$item = self::createFromDatabaseRow($result);

			return $item;
		} else {
			return false;
		}
	}

	public static function create($type) {
		$pdo = DataBase::getInstance();

		$gettingSortQuery = "SELECT MAX(sort) as recent FROM " . ITEMS_TABLE . " WHERE type = :type";
		$gettingSort = $pdo->prepare($gettingSortQuery);
		$gettingSort->bindValue(':type', $type, PDO::PARAM_INT);
		$gettingSort->execute();
		$result = $gettingSort->fetch();
		$order = $result['recent'] + 10;

		$query = "INSERT INTO " . ITEMS_TABLE . " (id, type, sort) VALUES(NULL, :type, :sort)";
		$creating = $pdo->prepare($query);
		$creating->bindValue(':type', $type, PDO::PARAM_INT);
		$creating->bindValue(':sort', $order, PDO::PARAM_INT);
		$creating->execute();

		$id = $pdo->lastInsertId();

		return new self($id, $type, $order);
	}

	public static function createFromDatabaseRow($row) {
		$fields = self::$fields;
		$languages = self::$languages;
		$languages_length = count($languages);
		$fields_length = count($fields);

		$id = $row['id'];
		$parent_id = $row['parent_id'];
		$type = $row['type'];
		$category = $row['category'];
		$order = $row['sort'];
		$visible = $row['visible'];

		$item = new self($id, $type, $order);
		$item->parentId = $parent_id;
		$item->visible = $visible;

		for($i = 0; $i < $languages_length; $i++) {
			for($j = 0; $j < $fields_length; $j++) {
				$field = $fields[$j] . "_" . $languages[$i];
				if(isset($row[$field])) {
					$item->setContent($languages[$i], $fields[$j], $row[$field]);
				}
			}
		}

		return $item;
	}

	public static function getFields() {
		return self::$fields;
	}

	public static function getDatabaseFieldname($field, $lang) {
		return $field . '_' . $lang;
	}

	public static function getLanguages() {
		return self::$languages;
	}

	public function getId() {
		return $this->id;
	}

	public function __toString() {
		return "#" . $this->id . "[" . $this->type . " - ". $this->getContent('header_1') . "]";
	}
}

?>