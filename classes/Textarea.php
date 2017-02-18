<?php

class Textarea extends FormField {

	public function get(Twig_Environment $twig) {
		return $twig->render('textarea.tpl', ['id' => $this->id, 'value' => $this->value]);
	}
}

?>