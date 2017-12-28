<?php

class Category extends Component implements Languagable, Orderable {
	public $parentId;
	public $type;

	const HEADER_1 = 'header_1';

	protected static $fields = [self::HEADER_1];

	public function __construct($id, $type, $order) {
		$this->id = $id;
		$this->type = $type;
		$this->parentId = 0;
		$this->order = $order;

		$this->contents = new Contents();
	}

	public function getId() {
		return $this->id;
	}

	public function save() {
		$fields = self::$fields;
		$languages = self::$languages;
		$languages_length = count($languages);
		$fields_length = count($fields);
		$current;

		$pdo = DataBase::getInstance();

		$query = "UPDATE " . CATEGORIES_TABLE . " SET parent_id = :parent_id, type = :type, sort = :sort";

		for($i = 0; $i < $languages_length; $i++) {
			for($j = 0; $j < $fields_length; $j++) {
				$current = self::getDatabaseFieldname($fields[$j], $languages[$i]);
				$query .= ", " . $current . " = :" . $current;
			}
		}

		$query .= " WHERE id = :id";

		$loading = $pdo->prepare($query);
		$loading->bindValue(':id', $this->id, PDO::PARAM_INT);
		$loading->bindValue(':parent_id', $this->parentId, PDO::PARAM_INT);
		$loading->bindValue(':type', $this->type, PDO::PARAM_INT);
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
		$query = "DELETE FROM " . CATEGORIES_TABLE . " WHERE id = :id";

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
		$query = "SELECT * FROM " . CATEGORIES_TABLE . " WHERE type = :type AND sort <= :sort AND id != :id ORDER BY sort DESC";

		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':id', $this->id, PDO::PARAM_INT);
		$loading->bindValue(':type', $this->type, PDO::PARAM_INT);
		$loading->bindValue(':sort', $this->order, PDO::PARAM_INT);
		$loading->execute();

		if($row = $loading->fetch()) {
			return self::createFromDatabaseRow($row);
		} else {
			return new NoSuchCategory();
		}
	}

	public function getLaterOne() {
		$query = "SELECT * FROM " . CATEGORIES_TABLE . " WHERE type = :type AND sort >= :sort AND id != :id ORDER BY sort ASC";

		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':id', $this->id, PDO::PARAM_INT);
		$loading->bindValue(':type', $this->type, PDO::PARAM_INT);
		$loading->bindValue(':sort', $this->order, PDO::PARAM_INT);
		$loading->execute();

		if($row = $loading->fetch()) {
			return self::createFromDatabaseRow($row);
		} else {
			return new NoSuchCategory();
		}
	}

	public static function load($id) {
		$query = "SELECT * FROM " . CATEGORIES_TABLE . " WHERE id = :id";

		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':id', $id, PDO::PARAM_INT);

		if($loading->execute()) {
			$result = $loading->fetch();

			$category = self::createFromDatabaseRow($result);

			return $category;
		} else {
			return false;
		}
	}

	public static function create($type) {
		$pdo = DataBase::getInstance();

		$gettingSortQuery = "SELECT MAX(sort) as recent FROM " . CATEGORIES_TABLE . " WHERE type = :type";
		$gettingSort = $pdo->prepare($gettingSortQuery);
		$gettingSort->bindValue(':type', $type, PDO::PARAM_INT);
		$gettingSort->execute();

		if($result = $gettingSort->fetch()) {
			$order = $result['recent'] + 10;
		} else {
			$order = 10;
		}
		

		$query = "INSERT INTO " . CATEGORIES_TABLE . " (id, type, sort) VALUES(NULL, :type, :sort)";
		$creating = $pdo->prepare($query);
		$creating->bindValue(':type', $type, PDO::PARAM_INT);
		$creating->bindValue(':sort', $order, PDO::PARAM_INT);
		$creating->execute();

		$id = $pdo->lastInsertId();

		return new self($id, $type, $order);
	}

	public function getContentsByLanguage($lang) {
		$current;
		$contents = [];

		$contents['id'] = $this->getId();
		$contents['type'] = $this->type;
		$contents['parentId'] = $this->parentId;
		$contents['order'] = $this->order;
		$contents['visible'] = $this->visible;

		if($current = $this->contents->getContentsByLanguage($lang)) {
			$contents = array_merge($contents, $current);
		}

		return $contents;
	}

	public static function createFromDatabaseRow($row) {
		$fields = self::$fields;
		$languages = self::$languages;
		$languages_length = count($languages);
		$fields_length = count($fields);

		$id = $row['id'];
		$parent_id = $row['parent_id'];
		$type = $row['type'];
		$order = $row['sort'];

		$category = new self($id, $type, $order);
		$category->parentId = $parent_id;

		for($i = 0; $i < $languages_length; $i++) {
			for($j = 0; $j < $fields_length; $j++) {
				$field = self::getDatabaseFieldname($fields[$j], $languages[$i]);
				if(isset($row[$field])) {
					$category->setContent($languages[$i], $fields[$j], $row[$field]);
				}
			}
		}

		return $category;
	}
}

?>