<?php

class DataCollection implements LanguagableCollection {
    private $data;

    public function __construct() {
        $this->data = [];
    }

    public function add(Data $data) {
        $this->data[] = $data;
    }

    public function getItems() {
        return $this->data;
    }

    public function getContentsByLanguage($lang) {
        $contents = [];

        foreach($this->data as $data) {
            $coontents[] = $data->getContentsByLanguage($lang);
        }

        return $contents;
    }

    public function size() {
        return count($this->data);
    }

    public static function load() {
        $query = "SELECT * FROM " . DATA_TABLE . " ORDER BY code ASC";

        $pdo = DataBase::getInstance();
        $result = $pdo->query($query);

        $collection = new self();

        foreach($result as $data) {
            $collection->add(Data::createFromDatabaseRow($data));
        }

        return $collection;
    }
}

?>