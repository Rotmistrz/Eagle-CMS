<?php

class Button implements FormItem {
	public $type;
	public $value;

	public function get(Twig_Environment $twig) {
		return $twig->render('button.tpl', ['type' => $this->type, 'value' => $this->value]);
	}
}

?>