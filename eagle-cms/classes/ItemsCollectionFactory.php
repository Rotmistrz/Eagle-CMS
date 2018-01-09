<?php

class ItemsCollectionFactory extends CollectionFactory {
	/**
	 *
	 * @param $items - item id's array
	 *
	**/
	public function loadGalleries($items) {
		$query = "SELECT * FROM " . GALLERIES_TABLE . " WHERE item_id IN(" . implode(', ', array_fill(0, count($items), '?')) . ") ORDER BY sort ASC";

		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare($query);
		
		$i = 1;

		foreach($items as $id) {
			$loading->bindValue($i++, $id);
		}
		
		$loading->execute();

		$galleries = [];

		while($result = $loading->fetch()) {
			$gallery = GalleryPicture::createFromDatabaseRow($result);

			if(array_key_exists($gallery->getItemId(), $galleries)) {
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

		return $galleries;
	}

	public function load($type) {
		$query = "SELECT * FROM " . ITEMS_TABLE . " WHERE type = :type";

		if(!$this->loadHiddenItems) {
			$query .= " AND visible != 0";
		}

		$query .= " ORDER BY sort " . $this->orderType;

		if($this->limit != self::NO_LIMIT) {
			$query .= " LIMIT " . $this->limit;
		}

		if($this->offset != self::NO_OFFSET) {
			$query .= " OFFSET " . $this->offset;
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
			$ids[] = $result['id'];
			$items[] = $result;
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

		if($this->limit != self::NO_LIMIT) {
			$query .= " LIMIT " . $this->limit;
		}

		if($this->offset != self::NO_OFFSET) {
			$query .= " OFFSET " . $this->offset;
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

		if($this->limit != self::NO_LIMIT) {
			$query .= " LIMIT " . $this->limit;
		}

		if($this->offset != self::NO_OFFSET) {
			$query .= " OFFSET " . $this->offset;
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
}

?>