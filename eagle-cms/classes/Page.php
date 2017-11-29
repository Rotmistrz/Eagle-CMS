<?php

class Page implements Languagable {
    private $id;
    private $slug;

    private $contents; // Contents

    const TITLE = 'title';
    const DESCRIPTION = 'description';

    private static $languages = [Language::PL, Language::EN];
    private static $fields = [self::TITLE, self::DESCRIPTION];

    public function __construct($id, $slug) {
        $this->id = $id;
        $this->slug = $slug;

        $this->contents = new Contents();
    }

    public function getId() {
        return $this->id;
    }

    public function setSlug($slug) {
        $this->slug = $slug;

        return $this;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function save() {
        $fields = self::$fields;
        $languages = self::$languages;
        $languages_length = count($languages);
        $fields_length = count($fields);
        $current;

        if(self::slugExists($this->slug, $this->id)) {
            throw new PageSlugExistsException();
        }
        
        $pdo = DataBase::getInstance();

        $query = "UPDATE " . PAGES_TABLE . " SET slug = :slug";

        for($i = 0; $i < $languages_length; $i++) {
            for($j = 0; $j < $fields_length; $j++) {
                $current = self::getDatabaseFieldname($fields[$j], $languages[$i]);
                $query .= ", " . $current . " = :" . $current;
            }
        }

        $query .= " WHERE id = :id";

        $updating = $pdo->prepare($query);
        $updating->bindValue(':id', $this->id, PDO::PARAM_INT);
        $updating->bindValue(':slug', $this->slug, PDO::PARAM_STR);

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
        if(self::slugExists($slug)) {
            throw new PageSlugExistsException();
        }

        $pdo = DataBase::getInstance();

        $query = "INSERT INTO " . PAGES_TABLE . " (id, slug) VALUES(NULL, :slug)";
        $creating = $pdo->prepare($query);
        $creating->bindValue(':slug', $slug, PDO::PARAM_STR);
        $creating->execute();

        $id = $pdo->lastInsertId();

        return new self($id, $slug);
    }

    public static function slugExists($slug, $id = 0) {
        $pdo = DataBase::getInstance();

        $checkingUniqueQuery = "SELECT * FROM " . PAGES_TABLE . " WHERE slug = :slug AND id != :id";
        $checkingUnique = $pdo->prepare($checkingUniqueQuery);
        $checkingUnique->bindValue(':slug', $slug, PDO::PARAM_STR);
        $checkingUnique->bindValue(':id', $id, PDO::PARAM_INT);
        $checkingUnique->execute();

        if($checkingUnique->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
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
                    $page->setContent($languages[$i], $fields[$j], $row[$field]);
                }
            }
        }

        return $page;
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