<?php

class ContentManager {
	public $lang;
	public $template;
	public $twig;

	public function __construct() {

	}

	public function getAllItemsByType($id) {
		$items = ItemsCollection::create($id);

		return $this->twig->render($this->template, array('itemsCollection' => $items->getContentsByLanguage($this->lang)));
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