<?php

class FileType {
    const JPG = "image/jpeg";
    const PNG = "image/png";
    const GIF = "image/gif";

    const UNDEFINED = "undefined";

    public static function getExtension($type) {
        $extension;

        if ($type == self::JPG) {
            $extension = "jpg";
        } else if ($type == self::PNG) {
            $extension = "png";
        } else if ($type == self::GIF) {
            $extension = "gif";
        } else {
            $extension = self::UNDEFINED;
        }

        return $extension;
    }
}

?>