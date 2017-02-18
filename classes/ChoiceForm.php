<?php

class ChoiceForm extends Form {
	public $title;
	public $back;

	public function get() {
		return $this->twig->render('form-choice.tpl', ['id' => $this->id, 'action' => $this->action, 'back' => $this->back, 'title' => $this->title]);
	}
}

?>