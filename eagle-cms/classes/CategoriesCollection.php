<?php

class CategoriesCollection implements LanguagableCollection {
	private $categories = [];

	public function __construct() {

	}

	public function add(Category $category) {
		$this->categories[] = $category;
	}

	public function getContentsByLanguage($lang) {
		$current;
		$i = 0;
		$contents = [];

		foreach($this->categories as $category) {
			$contents[$i]['id'] = $category->getId();
			$contents[$i]['type'] = $category->type;
			$contents[$i]['parentId'] = $category->parentId;
			$contents[$i]['order'] = $category->order;

			if($current = $category->getContentsByLanguage($lang)) {
				$contents[$i] = array_merge($contents[$i], $current);
			}

			$i++;
		}

		return $contents;
	}

	public function getAsKeyValueArray($lang) {
		$array = [];

		foreach($this->categories as $category) {
			$array[$category->getId()] = $category->getContent($lang, Category::HEADER_1);
		}

		return $array;
	}

	public static function load($type) {
		$query = "SELECT * FROM " . CATEGORIES_TABLE . " WHERE type = :type ORDER BY sort ASC";
		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':type', $type, PDO::PARAM_INT);
		$loading->execute();

		if($loading->rowCount() == 0) {
			return new self();
		}

		$collection = new self();
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