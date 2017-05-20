<?php

class ContentManager {
	public $lang;
	public $template;
	public $twig;

	public function __construct($twig) {
		$this->twig = $twig;
		$this->lang = Language::PL;
	}

	public function getAllItemsByType($id) {
		$items = ItemsCollection::load($id);

		return $this->twig->render($this->template, array('itemsCollection' => $items->getContentsByLanguage($this->lang)));
	}

	public function getAllItemsByCategory($category) {
		$items = ItemsCollection::loadByCategory($category);

		return $this->twig->render($this->template, array('itemsCollection' => $items->getContentsByLanguage($this->lang)));
	}

	public function getAllCategoriesByType($id) {
		$categories = CategoriesCollection::load($id);

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