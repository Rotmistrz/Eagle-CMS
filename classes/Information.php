<?php

class Information {
	const CORRECT = 0;
	const WARNING = 1;
	const ERROR = 2;

	public $type;
	public $content;

	public function __construct($type, $content) {
		$this->type = $type;
		$this->content = $content;
	}
}

?>