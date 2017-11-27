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

    public static function create($slug) {
        $pdo = DataBase::getInstance();

        $checkingUniqueQuery = "SELECT * FROM " . PAGES_TABLE . " WHERE slug = :slug";
        $checkingUnique = $pdo->prepare($checkingUniqueQuery);
        $checkingUnique->bindValue(':slug', $slug, PDO::PARAM_STR);
        
        if($checkingUnique->execute()) {
            
        }

        $query = "INSERT INTO " . PAGES_TABLE . " (id, slug) VALUES(NULL, :slug)";
        $creating = $pdo->prepare($query);
        $creating->bindValue(':slug', $slug, PDO::PARAM_STR);
        $creating->execute();

        $id = $pdo->lastInsertId();

        return new self($id, $slug);
    }

    public static function createFromDatabaseRow($row) {
        $fields = self::$fields;
        $languages = self::$languages;
        $languages_length = count($languages);
        $fields_length = count($fields);

        $id = $row['id'];
        $slug = $row['slug'];

        $page = new self($id, $slug);

        for($i = 0; $i < $languages_length; $i++) {
            for($j = 0; $j < $fields_length; $j++) {
                $field = self::getDatabaseFieldname($fields[$j], $languages[$i]);

                if(isset($row[$field])) {
                    $item->setContent($languages[$i], $fields[$j], $row[$field]);
                }
            }
        }

        return $item;
    }

    public function setContent($lang, $field, $value) {
        $this->contents->set($lang, $field, $value);

        return $this;
    }

    public function getContent($lang, $field) {
        return $this->contents->get($lang, $field);
    }

    public function getContentsByLanguage($lang) {
        $current;
        $contents = [];

        $contents['id'] = $this->id;
        $contents['slug'] = $this->slug;

        if($current = $this->contents->getContentsByLanguage($lang)) {
            $contents = array_merge($contents, $current);
        }

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