<?php

class Item extends Component implements Hideable {
	public $parentId;
	private $categories; // CategoriesList
	private $gallery; // GalleryPicturesCollection

	const HEADER_1 = 'header_1';
	const HEADER_2 = 'header_2';
	const HEADER_3 = 'header_3';
	const HEADER_4 = 'header_4';
	const HEADER_5 = 'header_5';

	const CONTENT_1 = 'content_1';
	const CONTENT_2 = 'content_2';
	const CONTENT_3 = 'content_3';

	protected static $fields = [self::HEADER_1, self::HEADER_2, self::HEADER_3, self::HEADER_4, self::HEADER_5, self::CONTENT_1, self::CONTENT_2, self::CONTENT_3];

	public function __construct($id, $type, $order) {
		$this->id = $id;
		$this->type = $type;
		$this->order = $order;
		$this->categories = new NoCategory();
		$this->gallery = new GalleryPicturesCollection();
		$this->visible = 1;
		$this->parentId = 0;

		$this->contents = new Contents();
	}

	public function setCategories(CategoriesList $categories) {
		$this->categories = $categories;
	}

	public function setGallery(GalleryPicturesCollection $gallery) {
		$this->gallery = $gallery;
	}

	public function save() {
		$fields = self::$fields;
		$languages = self::$languages;
		$languages_length = count($languages);
		$fields_length = count($fields);
		$current;
		$categories;

		$pdo = DataBase::getInstance();

		$query = "UPDATE " . ITEMS_TABLE . " SET parent_id = :parent_id, type = :type, category = :category, sort = :sort";

		for($i = 0; $i < $languages_length; $i++) {
			for($j = 0; $j < $fields_length; $j++) {
				$current = self::getDatabaseFieldname($fields[$j], $languages[$i]);
				$query .= ", " . $current . " = :" . $current;
			}
		}

		$query .= " WHERE id = :id";

		if(get_class($this->categories) == 'NoCategory') {
			$categories = null;
		} else {
			$categories = $this->categories->getDatabaseFormat();
		}

		$loading = $pdo->prepare($query);
		$loading->bindValue(':id', $this->id, PDO::PARAM_INT);
		$loading->bindValue(':parent_id', $this->parentId, PDO::PARAM_INT);
		$loading->bindValue(':type', $this->type, PDO::PARAM_INT);
		$loading->bindValue(':category', $categories, PDO::PARAM_INT);
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

	public function hide() {
		$query = "UPDATE " . ITEMS_TABLE . " SET visible = 0 WHERE id = :id";

		$pdo = DataBase::getInstance();
		$hiding = $pdo->prepare($query);
		$hiding->bindValue(':id', $this->id, PDO::PARAM_INT);

		if($hiding->execute()) {
			return true;
		} else {
			return false;
		}
	}

	public function show() {
		$query = "UPDATE " . ITEMS_TABLE . " SET visible = 1 WHERE id = :id";

		$pdo = DataBase::getInstance();
		$showing = $pdo->prepare($query);
		$showing->bindValue(':id', $this->id, PDO::PARAM_INT);

		if($showing->execute()) {
			return true;
		} else {
			return false;
		}
	}

	public function getEarlierOne() {
		$query = "SELECT * FROM " . ITEMS_TABLE . " WHERE type = :type AND sort <= :sort AND id != :id ORDER BY sort DESC LIMIT 1";

		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':id', $this->id, PDO::PARAM_INT);
		$loading->bindValue(':type', $this->type, PDO::PARAM_INT);
		$loading->bindValue(':sort', $this->order, PDO::PARAM_INT);
		$loading->execute();

		if($row = $loading->fetch()) {
			return self::createFromDatabaseRow($row);
		} else {
			return new NoSuchItem();
		}
	}

	public function getLaterOne() {
		$query = "SELECT * FROM " . ITEMS_TABLE . " WHERE type = :type AND sort >= :sort AND id != :id ORDER BY sort ASC LIMIT 1";

		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':id', $this->id, PDO::PARAM_INT);
		$loading->bindValue(':type', $this->type, PDO::PARAM_INT);
		$loading->bindValue(':sort', $this->order, PDO::PARAM_INT);
		$loading->execute();

		if($row = $loading->fetch()) {
			return self::createFromDatabaseRow($row);
		} else {
			return new NoSuchItem();
		}
	}

	public static function load($id) {
		$query = "SELECT * FROM " . ITEMS_TABLE . " WHERE id = :id";

		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':id', $id, PDO::PARAM_INT);

		if($loading->execute()) {
			$result = $loading->fetch();

			return self::createFromDatabaseRow($result);
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
		$categories = CategoriesList::createFromDatabaseFormat($row['category']);
		$order = $row['sort'];
		$visible = $row['visible'];

		$item = new self($id, $type, $order);
		$item->parentId = $parent_id;
		$item->visible = $visible;
		$item->setCategories($categories);

		for($i = 0; $i < $languages_length; $i++) {
			for($j = 0; $j < $fields_length; $j++) {
				$field = self::getDatabaseFieldname($fields[$j], $languages[$i]);

				if(isset($row[$field])) {
					$item->setContent($languages[$i], $fields[$j], $row[$field]);
				}
			}
		}

		return $item;
	}

	public function getContentsByLanguage($lang) {
		$current;
		$contents = [];

		$contents['id'] = $this->getId();
		$contents['type'] = $this->type;
		$contents['parentId'] = $this->parentId;
		$contents['order'] = $this->order;
		$contents['visible'] = $this->visible;

		if(get_class($this->gallery) == "NoGalleryPicturesCollection") {
			$contents['gallery'] = [];
		} else {
			$contents['gallery'] = $this->gallery->getContentsByLanguage($lang);
		}

		if($current = $this->contents->getContentsByLanguage($lang)) {
			$contents = array_merge($contents, $current);
		}

		return $contents;
	}

	public function getCategoriesArray() {
		return $this->categories->get();
	}

	public function __toString() {
		return "#" . $this->id . "[" . $this->type . " - ". $this->getContent(Language::PL, 'header_1') . " (" . $this->order . ")]";
	}
}

?>