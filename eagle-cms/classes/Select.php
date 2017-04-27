<?php

class Select implements FormItem {
	private $options = [];
	private $defaultValue;

	public function get(Twig_Environment $twig) {
		return $twig->render('select.tpl', ['id' => $this->id, 'default' => $this->defaultValue, 'optionsCollection' => $this->options]);
	}

	public function addOption($value, $name) {
		$this->options[] = ['value' => $value, 'name' => $name];
	}

	public function setDefaultValue($value) {
		$this->defaultValue = $value;
	}
}

?>