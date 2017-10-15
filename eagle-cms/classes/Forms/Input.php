<?php 

class Input extends FormField {
	public $type;

	public function get(Twig_Environment $twig) {
		return $twig->render('input.tpl', ['id' => $this->id, 'type' => $this->type, 'value' => $this->value]);
	}
}

?>