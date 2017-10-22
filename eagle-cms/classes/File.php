<?php

class File {
    public $type;
    public $directory;
    public $filename;

    public function __construct($type, $directory, $filename) {
        $this->type = $type;
        $this->directory = $directory;
        $this->filename = $filename;
    }

    public function fileExists() {
        return file_exists($this->getPath());
    }

    public function getContents() {
        $contents = [];

        $contents['path'] = $this->getPath();
        $contents['extension'] = FileType::getExtension($this->type);
        $contents['isPicture'] = $this->isPicture();

        return $contents;
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