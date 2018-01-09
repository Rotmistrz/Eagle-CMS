<?php

class GalleryPicture extends Component implements Orderable, Editable {
    protected $id;
    public $itemId;
    private $type; // FileType
    public $order;

    const TITLE = 'title';
    const DESCRIPTION = 'description';

    protected static $fields = [self::TITLE, self::DESCRIPTION];

    const THUMBNAIL = '-thumbnail';
    const SQUARE = '-square';

    public function __construct($id, $item_id, $type, $order) {
        $this->id = $id;
        $this->itemId = $item_id;
        $this->type = $type;
        $this->order = $order;

        $this->contents = new Contents();
    }

    public function getId() {
        return $this->id;
    }

    public function getItemId() {
        return $this->itemId;
    }

    public function setType(FileType $type) {
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    public function save() {
        $FileUploader = new FileUploader(ROOT . '/' . GALLERIES_DIR . '/');

        $requiredTypes = array(FileType::JPG, FileType::PNG);

        $type = $FileUploader->addFile('gallery-picture', $this->id, $requiredTypes, 1000000);

        if($type && !in_array($type, $requiredTypes)) {
            throw new Exception("Plik powinien mieć rozszerzenie .jpg lub .png.");
        }

        if(!$FileUploader->upload()) {
            throw new Exception("There has been occured an error while uploading the picture: " . $FileUploader->getErrorsAsString());
        }

        if(in_array($type, $requiredTypes)) {
            $this->type = $type;
        }

        $fields = self::$fields;
        $languages = self::$languages;
        $languages_length = count($languages);
        $fields_length = count($fields);
        $current;

        $pdo = DataBase::getInstance();

        $query = "UPDATE " . GALLERIES_TABLE . " SET type = :type, sort = :sort";

        for($i = 0; $i < $languages_length; $i++) {
            for($j = 0; $j < $fields_length; $j++) {
                $current = self::getDatabaseFieldname($fields[$j], $languages[$i]);
                $query .= ", " . $current . " = :" . $current;
            }
        }

        $query .= " WHERE id = :id";

        $updating = $pdo->prepare($query);
        $updating->bindValue(':id', $this->id, PDO::PARAM_INT);
        $updating->bindValue(':type', $this->type, PDO::PARAM_INT);
        $updating->bindValue(':sort', $this->order, PDO::PARAM_INT);

        for($i = 0; $i < $languages_length; $i++) {
            for($j = 0; $j < $fields_length; $j++) {
                $current = $fields[$j] . "_" . $languages[$i];

                $updating->bindValue(':' . $current, $this->contents->get($languages[$i], $fields[$j]), PDO::PARAM_STR);
            }
        }

        if($updating->execute()) {
            $imageResizer = new \Eventviva\ImageResize($this->getPath());
            $imageResizer->crop(200, 200)->save($this->getPath(self::SQUARE));
            $imageResizer->crop(600, 400)->save($this->getPath(self::THUMBNAIL));

            return true;
        } else {
            return false;
        }
    }

    public function getPath($sufix = '', $deep = true) {
        $path = "";

        if($deep) {
            $path .= ROOT;
        }

        $path .= "/" . GALLERIES_DIR . "/" . $this->id . $sufix . "." . FileType::getExtension($this->type);

        return $path;
    }

    public function getEarlierOne() {
        $query = "SELECT * FROM " . GALLERIES_TABLE . " WHERE item_id = :item_id AND sort <= :sort AND id != :id ORDER BY sort DESC LIMIT 1";

        $pdo = DataBase::getInstance();
        $loading = $pdo->prepare($query);
        $loading->bindValue(':id', $this->id, PDO::PARAM_INT);
        $loading->bindValue(':item_id', $this->itemId, PDO::PARAM_INT);
        $loading->bindValue(':sort', $this->order, PDO::PARAM_INT);
        $loading->execute();

        if($row = $loading->fetch()) {
            return self::createFromDatabaseRow($row);
        } else {
            return new NoSuchGalleryPicture();
        }
    }

    public function getLaterOne() {
        $query = "SELECT * FROM " . GALLERIES_TABLE . " WHERE item_id = :item_id AND sort >= :sort AND id != :id ORDER BY sort ASC LIMIT 1";

        $pdo = DataBase::getInstance();
        $loading = $pdo->prepare($query);
        $loading->bindValue(':id', $this->id, PDO::PARAM_INT);
        $loading->bindValue(':item_id', $this->itemId, PDO::PARAM_INT);
        $loading->bindValue(':sort', $this->order, PDO::PARAM_INT);
        $loading->execute();

        if($row = $loading->fetch()) {
            return self::createFromDatabaseRow($row);
        } else {
            return new NoSuchGalleryPicture();
        }
    }

    public function delete() {
        $query = "DELETE FROM " . GALLERIES_TABLE . " WHERE id = :id";

        $pdo = DataBase::getInstance();
        $deleting = $pdo->prepare($query);
        $deleting->bindValue(':id', $this->id, PDO::PARAM_INT);

        if($deleting->execute() && unlink($this->getPath()) && unlink($this->getPath(self::SQUARE)) && unlink($this->getPath(self::THUMBNAIL))) {
            return true;
        } else {
            return false;
        }
    }

    public function getContentsByLanguage($lang) {
        $current;
        $contents = [];

        $extension = FileType::getExtension($this->type);

        $contents['id'] = $this->id;
        $contents['type'] = $this->type;
        $contents['itemId'] = $this->itemId;
        $contents['extension'] = $extension;
        $contents['order'] = $this->order;
        $contents['filename'] = [];
        $contents['filename']['large'] = $this->id . "." . $extension;
        $contents['filename']['thumbnail'] = $this->id . "-thumbnail." . $extension;
        $contents['filename']['square'] = $this->id . "-square." . $extension;

        if($current = $this->contents->getContentsByLanguage($lang)) {
            $contents = array_merge($contents, $current);
        }

        return $contents;
    }

    public static function create($item_id) {
        $order = self::getFollowingOrder($item_id);

        $pdo = DataBase::getInstance();

        $date = date('Y-m-d H:i:s');

        $query = "INSERT INTO " . GALLERIES_TABLE . " (item_id, sort, date) VALUES(:item_id, :sort, :date)";
        $creating = $pdo->prepare($query);
        $creating->bindValue(':item_id', $item_id, PDO::PARAM_INT);
        $creating->bindValue(':sort', $order, PDO::PARAM_INT);
        $creating->bindValue(':date', $date, PDO::PARAM_INT);
        $creating->execute();

        $id = $pdo->lastInsertId();

        return new self($id, $item_id, null, $order);
    }

    public static function getFollowingOrder($type) {
        $item_id = $type;

        $pdo = DataBase::getInstance();

        $gettingSortQuery = "SELECT MAX(sort) as recent FROM " . GALLERIES_TABLE . " WHERE item_id = :item_id";
        $gettingSort = $pdo->prepare($gettingSortQuery);
        $gettingSort->bindValue(':item_id', $item_id, PDO::PARAM_INT);
        $gettingSort->execute();

        $followingOrder;
        
        if ($result = $gettingSort->fetch()) {
            $followingOrder = $result['recent'] + Orderable::ORDER_STEP;
        } else {
            $followingOrder = Orderable::INITIAL_ORDER;
        }

        return $followingOrder;
    }

    public static function load($id) {
        $query = "SELECT * FROM ". GALLERIES_TABLE . " WHERE id = :id";

        $pdo = DataBase::getInstance();
        $loading = $pdo->prepare($query);
        $loading->bindValue(':id', $id, PDO::PARAM_INT);
        
        if($loading->execute()) {
            $result = $loading->fetch();

            return self::createFromDataBaseRow($result);
        } else {
            return new NoSuchGalleryPicture();
        }
    }

    public static function createFromDatabaseRow($row) {
        $fields = self::$fields;
        $languages = self::$languages;
        $languages_length = count($languages);
        $fields_length = count($fields);

        $id = $row['id'];
        $item_id = $row['item_id'];
        $type = $row['type'];
        $order = $row['sort'];

        $picture = new self($id, $item_id, $type, $order);

        for($i = 0; $i < $languages_length; $i++) {
            for($j = 0; $j < $fields_length; $j++) {
                $field = self::getDatabaseFieldname($fields[$j], $languages[$i]);

                if(isset($row[$field])) {
                    $picture->setContent($languages[$i], $fields[$j], $row[$field]);
                }
            }
        }

        return $picture;
    }
}

?>