<?php

class DataDefinedCollection implements LanguagableCollection {
    private $data;

    public function __construct() {
        $this->data = [];
    }

    public function add(DataDefined $data) {
        $this->data[] = $data;

        return $this;
    }

    public function getItems() {
        return $this->data;
    }

    public function getContentsByLanguage($lang) {
        $contents = [];

        foreach($this->data as $data) {
            $contents[] = $data->getContentsByLanguage($lang);
        }

        return $contents;
    }

    public function size() {
        return count($this->data);
    }

    public static function load() {
        $query = "SELECT * FROM " . DATA_DEFINED_TABLE . " ORDER BY code ASC";

        $pdo = DataBase::getInstance();
        $result = $pdo->query($query);

        $collection = new self();

        foreach($result as $data) {
            $collection->add(DataDefined::createFromDatabaseRow($data));
        }

        return $collection;
    }
}

?>