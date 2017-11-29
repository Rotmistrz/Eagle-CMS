<?php

class PagesCollection implements LanguagableCollection {
    private $pages;

    public function __construct() {
        $this->pages = [];
    }

    public function add(Page $page) {
        $this->pages[] = $page;

        return $this;
    }

    public function getContentsByLanguage($lang) {
        $contents = [];

        foreach($this->pages as $page) {
            $contents[] = $page->getContentsByLanguage($lang);
        }

        return $contents;
    }

    public static function load() {
        $pdo = DataBase::getInstance();

        $query = "SELECT * FROM " . PAGES_TABLE . " ORDER BY slug ASC";
        $result = $pdo->query($query);

        $collection = new self();

        foreach($result as $data) {
            $collection->add(Page::createFromDatabaseRow($data));
        }

        return $collection;
    }
}

?>