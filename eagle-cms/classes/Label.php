<?php

class Label implements FormItem {
	public $title;
	private $formfield;

	public function __construct() {

	}

	public function setField(FormItem $formfield) {
		$this->formfield = $formfield;
	}

	public function get(Twig_Environment $twig) {
		return $twig->render('label.tpl', ['title' => $this->title, 'field' => $this->formfield->get($twig)]);
	}
}

?>