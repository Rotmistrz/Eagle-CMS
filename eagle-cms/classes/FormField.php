<?php

abstract class FormField implements FormItem {
	private $id;
	private $value;

	abstract public function get(Twig_Environment $twig);
}

?>