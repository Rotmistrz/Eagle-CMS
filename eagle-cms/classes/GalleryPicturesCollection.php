<?php

class GalleryPicturesCollection implements LanguagableCollection {
    private $pictures;

    public function __construct() {
        $this->pictures = [];
    }

    public function add(GalleryPicture $picture) {
        $this->pictures[] = $picture;
    }

    public function getContentsByLanguage($lang) {
        $contents = [];

        $len = count($this->pictures);

        for($i = 0; $i < $len; $i++) {
            $contents[] = $this->pictures[$i]->getContentsByLanguage($lang);
        }

        return $contents;
    }

    public static function load($item_id) {
        $query = "SELECT * FROM " . GALLERIES_TABLE . " WHERE item_id = :item_id ORDER BY sort ASC";

        $pdo = DataBase::getInstance();
        $loading = $pdo->prepare($query);
        $loading->bindValue(':item_id', $item_id, PDO::PARAM_INT);
        $loading->execute();

        $gallery = new self();

        while($result = $loading->fetch()) {
            $picture = GalleryPicture::createFromDataBaseRow($result);

            $gallery->add($picture);
        }

        return $gallery;
    }
}

?>