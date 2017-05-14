<?php

interface Languagable {
	public function setContent($lang, $field, $value);
	public function getContent($lang, $field);
	public function getContentsByLanguage($lang);

	public static function getFields();
	public static function getDatabaseFieldname($field, $lang);
	public static function getLanguages();
}

?>