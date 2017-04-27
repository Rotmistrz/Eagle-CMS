<?php

class Item {
	public $id;
	public $type;
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
		$updating = false;

		$pdo = DataBase::getInstance();

		if(!empty($this->id) && $this->id > 0) {
			$query .= "UPDATE " . ITEMS_TABLE . " SET type = :type, category = :category, sort = :sort";

			for($i = 0; $i < $languages_length; $i++) {
				for($j = 0; $j < $fields_length; $j++) {
					$current = self::getDatabaseFieldname($fields[$j], $languages[$i]);
					$query .= ", " . $current . " = :" . $current;
				}
			}

			$query .= " WHERE id = :id";

			$updating = true;
		} else {
			$gettingSortQuery = "SELECT MAX(sort) as recent FROM " . ITEMS_TABLE;
			$gettingSort = $pdo->query($gettingSortQuery);
			$sort = $gettingSort->fetch();
			$this->order = $sort['recent'];

			$content .= $this->order;

			$query .= "INSERT INTO " . ITEMS_TABLE . " (type, category, sort";

			for($i = 0; $i < $languages_length; $i++) {
				for($j = 0; $j < $fields_length; $j++) {
					$current = self::getDatabaseFieldname($fields[$j], $languages[$i]);
					$query .= ", " . $current;
				}
			}

			$query .= ") VALUES(:type, :category, :sort";

			for($i = 0; $i < $languages_length; $i++) {
				for($j = 0; $j < $fields_length; $j++) {
					$current = self::getDatabaseFieldname($fields[$j], $languages[$i]);
					$query .= ", :" . $current;
				}
			}

			$query .= ")";
		}

		if(!is_null($this->category)) {
			$category_id = $this->category->getId();
		} else {
			$category_id = 0;
		}

		
		$loading = $pdo->prepare($query);

		if($updating) {
			$loading->bindValue(':id', $this->id, PDO::PARAM_INT);
		}

		$loading->bindValue(':type', $this->type, PDO::PARAM_INT);
		$loading->bindValue(':category', $category_id, PDO::PARAM_INT);
		$loading->bindValue(':sort', $this->order, PDO::PARAM_INT);

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

	public static function load($id) {
		$query = "SELECT * FROM " . ITEMS_TABLE . " WHERE id = :id";

		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':id', $id, PDO::PARAM_INT);

		if($loading->execute()) {
			$result = $loading->fetch();

			$fields = self::$fields;
			$languages = self::$languages;
			$languages_length = count($languages);
			$fields_length = count($fields);

			$id = $result['id'];
			$type = $result['type'];
			$category = $result['category'];
			$order = $result['sort'];
			$visible = $result['visible'];

			$item = new self($id, $type, $order);

			for($i = 0; $i < $languages_length; $i++) {
				for($j = 0; $j < $fields_length; $j++) {
					$field = $fields[$j] . "_" . $languages[$i];
					if(isset($result[$field])) {
						$item->setContent($languages[$i], $fields[$j], $result[$field]);
					}
				}
			}

			return $item;
		} else {
			return false;
		}
	}

	public static function create() {
		$query = "INSERT INTO " . ITEMS_TABLE . " (id) VALUES(NULL)";

		$pdo = DataBase::getInstance();
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