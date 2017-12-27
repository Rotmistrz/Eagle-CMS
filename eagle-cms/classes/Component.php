<?php

abstract class Component implements Languagable, Orderable {
	protected $id;
	public $order;
	public $visible;
	protected $contents;

	protected static $languages = [Language::PL, Language::EN];
	protected static $fields = [];

	abstract public function getEarlierOne();
	abstract public function getLaterOne();

	abstract public function save();
	abstract public function delete();

	public function setVisible($visible) {
		if(is_bool($visible)) {
			$this->visible = (bool) $visible;
		} else {
			throw new Exception("Item::setVisible() must receive boolean value.");
		}
	}

	public function getVisible() {
		return $this->visible;
	}

	public function setOrder($order) {
		if(is_number($order)) {
			$this->order = $order;
		} else {
			throw new Exception("Item::setOrder() must receive integer value.");
		}

		return $this;
	}

	public function getOrder() {
		return $this->order;
	}

	public function setContent($lang, $field, $value) {
		$this->contents->set($lang, $field, $value);

		return $this;
	}

	public function getContent($lang, $field) {
		return $this->contents->get($lang, $field);
	}

	public function getContentsByLanguage($lang) {
		return $this->contents->getContentsByLanguage($lang);
	}

	public static function getFields() {
		return static::$fields;
	}

	public static function getDatabaseFieldname($field, $lang) {
		return $field . '_' . $lang;
	}

	public static function getLanguages() {
		return static::$languages;
	}

	public function getId() {
		return $this->id;
	}
}

?>