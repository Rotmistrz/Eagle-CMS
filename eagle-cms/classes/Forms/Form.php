<?php

abstract class Form {
	public $id;
	public $action;
	public $method;
	public $class;

	protected $twig;

	public function __construct(Twig_Environment $twig) {
		$this->twig = $twig;
	}

	public abstract function get();
}

?>