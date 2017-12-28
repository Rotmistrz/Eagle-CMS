<?php

abstract class LanguagableElement implements Languagable {
    protected $contents;

    protected static $languages = [Language::PL, Language::EN];
    protected static $fields = [];
    
    public function __construct() {
        $this->contents = new Contents();
    }

    abstract public function getContentsByLanguage($lang);

    public function setContent($lang, $field, $value) {
        $this->contents->set($lang, $field, $value);

        return $this;
    }

    public function getContent($lang, $field) {
        return $this->contents->get($lang, $field);
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
}

?>