<?php

class GalleryPicture {
    public $id;
    public $itemId;
    public $type;
    public $title;
    public $description;

    public function __construct($id, $item_id, $type) {
        $this->id = $id;
        $this->itemId = $item_id;
        $this->type = $type;
    }

    public function save() {
        $FileUploader = new FileUploader(ROOT . '/' . GALLERIES_DIR . '/');

        if(!$this->type = $FileUploader->addFile('gallery-picture', $this->id, array(FileType::JPG, FileType::PNG), 1000000)) {
            throw new Exception("Incorrect file type.");
        }

        if(!$FileUploader->upload()) {
            throw new Exception("There has been occured an error while uploading the picture: " . $FileUploader->getErrorsAsString());
        }

        $query = "UPDATE " . GALLERIES_TABLE . " SET type = :type, title = :title, description = :description WHERE id = :id";
        $pdo = DataBase::getInstance();
        $updating = $pdo->prepare($query);
        $updating->bindValue(':type', $this->type, PDO::PARAM_STR);
        $updating->bindValue(':title', $this->title, PDO::PARAM_STR);
        $updating->bindValue(':description', $this->description, PDO::PARAM_STR);
        $updating->bindValue(':id', $this->id, PDO::PARAM_INT);

        if($updating->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function create($item_id) {
        $query = "INSERT INTO " . GALLERIES_TABLE . " VALUES(NULL, :item_id, :type, :title, :description, :date)";

        $date = date('Y-m-d H:i:s');

        $pdo = DataBase::getInstance();
        $creating = $pdo->prepare($query);
        $creating->bindValue(':item_id', $item_id, PDO::PARAM_INT);
        $creating->bindValue(':type', null, PDO::PARAM_STR);
        $creating->bindValue(':title', null, PDO::PARAM_STR);
        $creating->bindValue(':description', null, PDO::PARAM_STR);
        $creating->bindValue(':date', $date, PDO::PARAM_STR);

        if(!$creating->execute()) {
            throw new Exception("GalleryPicture: Inserting to database failed.");
        }

        return new self($pdo->lastInsertId(), $item_id, null);
    }

    public static function load($id) {
        $query = "SELECT * FROM ". GALLERIES_TABLE . " WHERE id = :id";

        $pdo = DataBase::getInstance();
        $loading = $pdo->prepare($query);
        $loading->bindValue(':id', $id, PDO::PARAM_INT);
        $loading->execute();

        if($result = $loading->fetch()) {
            $picture = new self($id, $data['item_id'], $data['type']);
            $picture->title = $data['title'];
            $picture->description = $data['description'];

            return $picture;
        } else {
            return new NoSuchGalleryPicture();
        }
    }
}

?>