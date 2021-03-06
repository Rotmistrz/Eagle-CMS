<?php

class ContentManager {
	public $lang;
	public $template;
	public $twig;
	private $orderType; // Order
	private $loadHiddenItems; // boolean

	public function __construct($twig) {
		$this->twig = $twig;
		$this->lang = Language::PL;

		$this->orderType = Orderable::ASC;
		$this->loadHiddenItems = false;
	}

	public function setLoadHiddenItems($loadHiddenItems) {
		$this->loadHiddenItems = $loadHiddenItems;
	}

	public function setAscendingOrder() {
		$this->orderType = Orderable::ASC;

		return $this;
	}

	public function setDescendingOrder() {
		$this->orderType = Orderable::DESC;

		return $this;
	}

	public function getItemsByType($type, $offset, $limit) {
		$itemsFactory = new ItemsCollectionFactory();
		$itemsFactory->setLoadHiddenItems($this->loadHiddenItems);
		$itemsFactory->setOffset($offset);
		$itemsFactory->setLimit($limit);

		if($this->orderType == Orderable::DESC) {
			$itemsFactory->setDescendingOrder();
		}

		$items = $itemsFactory->load($type);

		return $this->twig->render($this->template, array('itemsCollection' => $items->getContentsByLanguage($this->lang)));
	}

	public function getAllItemsByType($type) {
		$itemsFactory = new ItemsCollectionFactory();
		$itemsFactory->setLoadHiddenItems($this->loadHiddenItems);

		if($this->orderType == Orderable::DESC) {
			$itemsFactory->setDescendingOrder();
		}

		$items = $itemsFactory->load($type);

		return $this->twig->render($this->template, array('type' => $type, 'itemsCollection' => $items->getContentsByLanguage($this->lang)));
	}

	public function getItemsByParent($parent, $type, $offset, $limit) {
		$itemsFactory = new ItemsCollectionFactory();
		$itemsFactory->setLoadHiddenItems($this->loadHiddenItems);
		$itemsFactory->setOffset($offset);
		$itemsFactory->setLimit($limit);

		if($this->orderType == Orderable::DESC) {
			$itemsFactory->setDescendingOrder();
		}

		$items = $itemsFactory->loadByParent($parent, $type);

		return $this->twig->render($this->template, array('itemsCollection' => $items->getContentsByLanguage($this->lang)));
	}

	public function getAllItemsByParent($parent, $type) {
		$itemsFactory = new ItemsCollectionFactory($this->loadHiddenItems);

		if($this->orderType == Order::DESC) {
			$itemsFactory->setDescendingOrder();
		}

		$items = $itemsFactory->loadByParent($parent, $type);

		return $this->twig->render($this->template, array('type' => $type, 'itemsCollection' => $items->getContentsByLanguage($this->lang)));
	}

	public function getItemsByCategory($category, $after, $limit) {
		$itemsFactory = new ItemsCollectionFactory($this->loadHiddenItems);
		$itemsFactory->after = $after;
		$itemsFactory->limit = $limit;

		if($this->orderType == Order::DESC) {
			$itemsFactory->setDescendingOrder();
		}

		$items = $itemsFactory->loadByCategory($category);

		return $this->twig->render($this->template, array('itemsCollection' => $items->getContentsByLanguage($this->lang)));
	}

	public function getAllItemsByCategory($category) {
		$itemsFactory = new ItemsCollectionFactory($this->loadHiddenItems);

		if($this->orderType == Order::DESC) {
			$itemsFactory->setDescendingOrder();
		}

		$items = $itemsFactory->loadByCategory($category);

		return $this->twig->render($this->template, array('itemsCollection' => $items->getContentsByLanguage($this->lang)));
	}

	public function getAllCategoriesByType($type) {
		$categoriesFactory = new CategoriesCollectionFactory($this->loadHiddenItems);

		if($this->orderType == Order::DESC) {
			$itemsFactory->setDescendingOrder();
		}
		
		$categories = $categoriesFactory->load($type);

		return $this->twig->render($this->template, array('categoriesCollection' => $categories->getContentsByLanguage($this->lang)));
	}

	public function getGallery($item_id) {
		$gallery = GalleryPicturesCollection::load($item_id);

		return $this->twig->render("manage-gallery.tpl", array('itemId' => $item_id, 'time' => time(), 'picturesCollection' => $gallery->getContentsByLanguage($this->lang)));
	}

	public function getAllPages() {
		$pages = PagesCollection::load();

		return $this->twig->render($this->template, array('pagesCollection' => $pages->getContentsByLanguage($this->lang)));
	}

	public function getAllDataDefined() {
		$dataDefined = DataDefinedCollection::load();

		return $this->twig->render($this->template, array('dataCollection' => $dataDefined->getContentsByLanguage($this->lang)));
	}

	public static function getTitle($title) {
		return "<h3>" . $title . "</h3>";
	}

	public function getCorrectMessage($message) {
		return $this->twig->render('message.tpl', ['class' => 'correct', 'message' => $message]);
	}

	public function getErrorMessage($message) {
		return $this->twig->render('message.tpl', ['class' => 'error', 'message' => $message]);
	}
}

?>