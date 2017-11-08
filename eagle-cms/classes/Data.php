<?php

class Data implements Languagable {
    private $id;
    private $code;
    private $values;

    protected static $languages = [Language::PL, Language::EN];

    public function __construct($id, $code) {
        $this->id = $id;
        $this->code = $code;

        $this->values = [];
    }

    public function getId() {
        return $this->id;
    }

    public function getCode() {
        return $this->code;
    }

    public function setValue($lang, $value) {
        $this->values[$lang] = $value;

        return $this;
    }

    public function getValue($lang) {
        return $this->values[$lang];
    }

    public static function load($id) {
        $query = "SELECT * FROM " . DATA_TABLE . " WHERE id = :id";

        $pdo = DataBase::getInstance();
        $loading = $pdo->prepare($query);
        $loading->bindValue(':id', $id, PDO::PARAM_INT);
        $loading->execute();

        if($result = $loading->fetch()) {
            return self::createFromDatabaseRow($result);
        } else {
            return new NoSuchData();
        }
    }

    public static function createFromDatabaseRow($row) {
        $id = $row['id'];
        $code = $row['code'];

        $data = new self($id, $code);

        $languages = self::$languages;
        $languages_length = count($languages);

        for($i = 0; $i < $languages_length; $i++) {
            $field = self::getDatabaseFieldname('value', $languages[$i]);

            $value = isset($row[$field]) ? $row[$field] : null;

            $data->setValue($languages[$i], $value);
        }

        return $data;
    }

    public function setContent($lang, $field, $value) {

    }
    
    public function getContent($lang, $field) {

    }

    public function getContentsByLanguage($lang) {
        return ['id' => $this->id, 'code' => $this->code, 'values' => $this->values];
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