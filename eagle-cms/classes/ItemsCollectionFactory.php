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

	/**
	 *
	 * @param $items - item id's array
	 *
	**/
	public function loadGalleries($items) {
		$query = "SELECT * FROM " . GALLERIES_TABLE . " WHERE item_id IN(:item_ids)";

		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':item_ids', implode(',', $items), PDO::PARAM_STR);
		$loading->execute();

		$galleries = [];

		while($result = $loading->fetch()) {
			$gallery = GalleryPicture::createFromDatabaseRow($result);

			if(array_key_exists($result['item_id'], $galleries)) {
				$collection = $galleries[$result['item_id']];
				$collection->add($gallery);
			} else {
				$collection = new GalleryPicturesCollection();
				$collection->add($gallery);

				$galleries[$result['item_id']] = $collection;
			}
		}

		foreach($items as $id) {
			if(!array_key_exists($id, $galleries)) {
				$galleries[$id] = new NoGalleryPicturesCollection();
			}
		}

		/**$query = "SELECT " . GALLERIES_TABLE . ".*, " . ITEMS_TABLE . ".id as i_id, type, " . ITEMS_TABLE . ".sort as i_sort FROM " . GALLERIES_TABLE . " LEFT JOIN " . ITEMS_TABLE . " ON item_id = i_id WHERE type = :type ORDER BY i_sort ASC";

		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		$loading->bindValue(':type', $type, PDO::PARAM_INT);
		$loading->execute();

		$galleries = [];

		while($result = $loading->fetch()) {
			$gallery = GalleryPicture::createFromDatabaseRow($result);

			if(!empty($result['item_id'])) {
				$collection = $galleries[$result['item_id']];
				$collection->add($gallery);
			} else {
				$collection = new GalleryPicturesCollection();
				$collection->add($gallery);

				$galleries[$result['i_id']] = $collection;
			}
		}**/

		return $galleries;
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

		$fields = Item::getFields();
		$languages = Item::getLanguages();
		$languages_length = count($languages);
		$fields_length = count($fields);

		$ids = [];
		$items = [];

		while($result = $loading->fetch()) {
			//$item = Item::createFromDatabaseRow($result);
			//$item->setGallery($galleries[$item->getId()]);

			$ids[] = $result['id'];
			$items[] = $result;

			//$collection->add($item);
		}

		$galleries = $this->loadGalleries($ids);

		foreach($items as $result) {
			$item = Item::createFromDatabaseRow($result);
			$item->setGallery($galleries[$result['id']]);

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