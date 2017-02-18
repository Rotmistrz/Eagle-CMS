<?php

class FileField extends FormField {
	public function get(Twig_Environment $twig) {
		return $twig->render('filefield.tpl', ['id' => $this->id]);
	}
}

?>