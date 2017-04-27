<?php

class InformationManager {
	private function __construct() {}
	
	public static function set(Information $information) {
		$_SESSION['information'] = ['type' => $information->type, 'content' => $information->content];
	}

	public static function get() {
		if(isset($_SESSION['information'])) {
			return new Information($_SESSION['information']['type'], $_SESSION['information']['content']);
		} else {
			return false;
		}
	}

	public static function clear() {
		unset($_SESSION['information']);
	}
}

?>