<?php

class DataDefined implements Languagable {
    private $id;
    private $code;

    private $contents;

    const VALUE = 'value';

    protected static $fields = [self::VALUE];
    protected static $languages = [Language::PL, Language::EN];

    public function __construct($id, $code) {
        $this->id = $id;
        $this->code = $code;

        $this->contents = new Contents();
    }

    public function getId() {
        return $this->id;
    }

    public function getCode() {
        return $this->code;
    }

    public function setValue($lang, $value) {
        $this->setContent($lang, self::VALUE, $value);

        return $this;
    }

    public function getValue($lang) {
        return $this->getContent($lang, self::VALUE);
    }

    public static function load($id) {
        $query = "SELECT * FROM " . DATA_DEFINED_TABLE . " WHERE id = :id";

        $pdo = DataBase::getInstance();
        $loading = $pdo->prepare($query);
        $loading->bindValue(':id', $id, PDO::PARAM_INT);
        $loading->execute();

        if($result = $loading->fetch()) {
            return self::createFromDatabaseRow($result);
        } else {
            return new NoSuchDataDefined();
        }
    }

    public static function createFromDatabaseRow($row) {
        $id = $row['id'];
        $code = $row['code'];

        $data = new self($id, $code);

        $fields = self::$fields;
        $languages = self::$languages;
        
        $fields_length = count($fields);
        $languages_length = count($languages);

        for($i = 0; $i < $languages_length; $i++) {
            for($j = 0; $j < $fields_length; $j++) {
                $field = self::getDatabaseFieldname($fields[$j], $languages[$i]);

                if(isset($row[$field])) {
                    $data->setContent($languages[$i], $fields[$j], $row[$field]);
                }
            }
        }

        return $data;
    }

    public function setContent($lang, $field, $value) {
        $this->contents->set($lang, $field, $value);

        return $this;
    }

    public function getContent($lang, $field) {
        return $this->contents->get($lang, $field);
    }

    public function getContentsByLanguage($lang) {
        $contents = [];

        $contents['id'] = $this->id;
        $contents['code'] = $this->code;

        $contents = array_merge($contents, $this->contents->getContentsByLanguage($lang));

        return $contents;
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