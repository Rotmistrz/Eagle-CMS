<?php

class Gallery {
    private $pictures;

    public function __construct() {
        $this->pictures = [];
    }

    public function addPicture(File $picture) {
        if(!$picture->isPicture()) {
            throw new FileTypeException("Gallery::addPicture() should receive picture file.");
        }

        $this->pictures[] = $picture;
    }

    public function load($itemId) {
        $query = "SELECT * FROM " . GALLERIES_TABLE . " WHERE item_id = :item_id ORDER BY id ASC";

        $pdo = DataBase::getInstance();
        $loading = $pdo->prepare($query);
        $loading->bindValue(':item_id', $itemId, PDO::PARAM_INT);
        $loading->execute();

        while($data = $loading->fetch()) {

        }
    }
}

?>