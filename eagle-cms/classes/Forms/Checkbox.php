<?php

class Checkbox extends FormField {
	public $name;
	private $checked; // boolean
	public $description;

	public function __construct($name, $value, $description) {
		$this->name = $name;
		$this->value = $value;
		$this->description = $description;
		$this->checked = false;
	}

	public function setChecked($checked) {
		$this->checked = $checked;
	}

	public function isChecked() {
		return $this->checked;
	}

	public function get(Twig_Environment $twig) {
		return $twig->render('checkbox.tpl', ['name' => $this->name, 'value' => $this->value]);
	}
}

?>