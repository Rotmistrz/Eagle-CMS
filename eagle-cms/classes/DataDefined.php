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

    public function setCode($code) {
        $this->code = $code;

        return $this;
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

    public function save() {
        if(self::codeExists($this->code, $this->id)) {
            throw new DataDefinedCodeExistsException("Taki kod już istnieje.");
        }

        if(!self::isCodeCorrect($this->code)) {
            throw new IncorrectDataDefinedCodeException("Niepoprawny kod danej zdefiniowanej.");
        }

        $fields = self::$fields;
        $languages = self::$languages;
        $languages_length = count($languages);
        $fields_length = count($fields);
        $current;

        $pdo = DataBase::getInstance();

        $query = "UPDATE " . DATA_DEFINED_TABLE . " SET code = :code";

        for($i = 0; $i < $languages_length; $i++) {
            for($j = 0; $j < $fields_length; $j++) {
                $current = self::getDatabaseFieldname($fields[$j], $languages[$i]);
                $query .= ", " . $current . " = :" . $current;
            }
        }

        $query .= " WHERE id = :id";

        $updating = $pdo->prepare($query);
        $updating->bindValue(':id', $this->id, PDO::PARAM_INT);
        $updating->bindValue(':code', $this->code, PDO::PARAM_STR);

        for($i = 0; $i < $languages_length; $i++) {
            for($j = 0; $j < $fields_length; $j++) {
                $current = $fields[$j] . "_" . $languages[$i];

                $updating->bindValue(':' . $current, $this->contents->get($languages[$i], $fields[$j]), PDO::PARAM_STR);
            }
        }

        if($updating->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        $query = "DELETE FROM " . DATA_DEFINED_TABLE . " WHERE id = :id";

        $pdo = DataBase::getInstance();
        $deleting = $pdo->prepare($query);
        $deleting->bindValue(':id', $this->id, PDO::PARAM_INT);

        if($deleting->execute()) {
            return true;
        } else {
            return false;
        }
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

    public static function create($code) {
        if(self::codeExists($code)) {
            throw new DataDefinedCodeExistsException("Taki kod już istnieje.");
        }

        if(!self::isCodeCorrect($code)) {
            throw new IncorrectDataDefinedCodeException("Niepoprawny kod danej zdefiniowanej.");
        }

        $pdo = DataBase::getInstance();

        $query = "INSERT INTO " . DATA_DEFINED_TABLE . " (id, code) VALUES(NULL, :code)";
        $creating = $pdo->prepare($query);
        $creating->bindValue(':code', $code, PDO::PARAM_STR);
        $creating->execute();

        $id = $pdo->lastInsertId();

        return new self($id, $code);
    }

    public static function isCodeCorrect($code) {
        $regex = "/^[a-zA-Z]+([a-zA-Z0-9_]*[a-zA-Z0-9]+)*$/";

        if(preg_match($regex, $code)) {
            return true;
        } else {
            return false;
        }
    }

    public static function codeExists($code, $id = 0) {
        $pdo = DataBase::getInstance();

        $checkingUniqueQuery = "SELECT * FROM " . DATA_DEFINED_TABLE . " WHERE code = :code AND id != :id";
        $checkingUnique = $pdo->prepare($checkingUniqueQuery);
        $checkingUnique->bindValue(':code', $code, PDO::PARAM_STR);
        $checkingUnique->bindValue(':id', $id, PDO::PARAM_INT);
        $checkingUnique->execute();

        if($checkingUnique->rowCount() > 0) {
            return true;
        } else {
            return false;
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