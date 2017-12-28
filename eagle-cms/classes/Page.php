<?php

class Page extends LanguagableElement implements Editable {
    private $id;
    private $slug;

    const TITLE = 'title';
    const DESCRIPTION = 'description';

    protected static $fields = [self::TITLE, self::DESCRIPTION];

    public function __construct($id, $slug) {
        parent::__construct();

        $this->id = $id;
        $this->slug = $slug;
    }

    public function getId() {
        return $this->id;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;

        return $this;
    }

    public function getTitle($lang) {
        return $this->getContent($lang, 'title');
    }

    public function setTitle($lang, $title) {
        $this->setContent($lang, 'title', $title);

        return $this;
    }

    public function getDescription($lang) {
        return $this->getContent($lang, 'description');
    }

    public function setDescription($lang, $description) {
        $this->setContent($lang, 'description', $description);

        return $this;
    }

    public function save() {
        if(self::slugExists($this->slug, $this->id)) {
            throw new PageSlugExistsException("Taki slug już istnieje.");
        }

        if(!self::isSlugCorrect($this->slug)) {
            throw new IncorrectPageSlugException("Niepoprawny slug strony.");
        }

        $fields = self::$fields;
        $languages = self::$languages;
        $languages_length = count($languages);
        $fields_length = count($fields);
        $current;
        
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

    public function delete() {
        $query = "DELETE FROM " . PAGES_TABLE . " WHERE id = :id";

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

    public static function loadBySlug($slug) {
        $query = "SELECT * FROM " . PAGES_TABLE . " WHERE slug = :slug";

        $pdo = DataBase::getInstance();
        $loading = $pdo->prepare($query);
        $loading->bindValue(':slug', $slug, PDO::PARAM_STR);

        if($loading->execute()) {
            $result = $loading->fetch();

            return self::createFromDatabaseRow($result);
        } else {
            return false;
        }
    }

    public static function create($slug) {
        if(self::slugExists($slug)) {
            throw new PageSlugExistsException("Taki slug już istnieje.");
        }

        if(!self::isSlugCorrect($slug)) {
            throw new IncorrectPageSlugException("Niepoprawny slug strony.");
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

    public static function isSlugCorrect($slug) {
        $regex = "/^[a-zA-Z]+([a-zA-Z0-9\-]*[a-zA-Z0-9]+)*$/";

        if(preg_match($regex, $slug)) {
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
}

?>