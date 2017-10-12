<?php

class File {
    public $type;
    public $directory;
    public $filename;

    public function __construct($type, $directory, $filename) {
        $this->type = $type;
        $this->directory = $directory;
        $this->filename = $filename;

        if(!file_exists($this->getPath())) {
            throw new FileNotFoundException();
        }
    }

    public function isPicture() {
        if($this->type == FileType::JPG || $this->type == FileType::PNG || $this->type == FileType::GIF) {
            return true;
        } else {
            return false;
        }
    }

    public function getPath() {
        return $this->directory . "/" . $this->filename . "." . FileType::getExtension($this->type);
    }
}

?>