<?php

class File {
    public $type;
    public $directory;
    public $filename;

    public function __construct() {

    }

    public function getPath() {
        return $this->directory . "/" . $this->filename . "." . FileType::getExtension($this->type);
    }
}

?>