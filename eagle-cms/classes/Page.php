<?php

class Page implements Languagable {
    private $id;
    private $slug;

    private $contents; // Contents

    const TITLE = 'title';
    const DESCRIPTION = 'description';

    private static $languages = [Language::PL, Language::EN];
    private static $fields = [TITLE, DESCRIPTION];

    public function __construct($id, $slug) {
        $this->id = $id;
        $this->slug = $slug;
    }

    public static function load($id) {
        $query = "SELECT * FROM " . PAGES_TABLE . " WHERE id = :id";

        $pdo = DataBase::getInstance();
        $loading = $pdo->prepare($query);
        $loading->bindValue(':id', $id, PDO::PARAM_INT);

        if($loading->execute()) {
            $result = $loading->fetch();

            return self::createFromDatabaseRow($result);
        } else {
            return false;
        }
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
        return self::$fields;
    }

    public static function getDatabaseFieldname($field, $lang) {
        return $field . "_" . $lang;
    }

    public static function getLanguages() {
        return self::$languages;
    }
}

?>