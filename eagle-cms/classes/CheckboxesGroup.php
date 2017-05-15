<?php 

class CheckboxesGroup extends FormField {
	public $checkboxes = [];
	public $name;

	public function __construct($name, $title) {
		$this->name = $name;
		$this->title = $title;
	}

	public function addValue(Checkbox $checkbox) {
		$this->checkboxes[] = ['name' => $checkbox->name, 'value' => $checkbox->value, 'description' => $checkbox->description, 'checked' => $checkbox->isChecked()];
	}

	public function get(Twig_Environment $twig) {
		return $twig->render('checkboxes-group.tpl', ['name' => $this->name, 'title' => $this->title, 'checkboxes' => $this->checkboxes]);
	}
}

?>