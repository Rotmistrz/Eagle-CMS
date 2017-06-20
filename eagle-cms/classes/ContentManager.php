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

		$this->orderType = Order::ASC;
		$this->loadHiddenItems = false;
	}

	public function setLoadHiddenItems($loadHiddenItems) {
		$this->loadHiddenItems = $loadHiddenItems;
	}

	public function setAscendingOrder() {
		$this->orderType = Order::ASC;

		return $this;
	}

	public function setDescendingOrder() {
		$this->orderType = Order::DESC;

		return $this;
	}

	public function getAllItemsByType($id) {
		$itemsFactory = new ItemsCollectionFactory($this->loadHiddenItems);
		$items = $itemsFactory->load($id);

		return $this->twig->render($this->template, array('itemsCollection' => $items->getContentsByLanguage($this->lang)));
	}

	public function getAllItemsByParent($id, $parent) {
		$itemsFactory = new ItemsCollectionFactory($this->loadHiddenItems);
		$items = $itemsFactory->loadByParent($id, $parent);

		return $this->twig->render($this->template, array('itemsCollection' => $items->getContentsByLanguage($this->lang)));
	}

	public function getAllItemsByCategory($category) {
		$itemsFactory = new ItemsCollectionFactory($this->loadHiddenItems);
		$items = $itemsFactory->loadByCategory($category);

		return $this->twig->render($this->template, array('itemsCollection' => $items->getContentsByLanguage($this->lang)));
	}

	public function getAllCategoriesByType($id) {
		$categoriesFactory = new CategoriesCollectionFactory($this->loadHiddenItems);
		$categories = $categoriesFactory->load($id);

		return $this->twig->render($this->template, array('categoriesCollection' => $categories->getContentsByLanguage($this->lang)));
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