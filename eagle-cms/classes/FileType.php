<?php

class FileType {
    const JPG = "image/jpeg";
    const PNG = "image/png";

    public static function getExtension($type) {
        $extension;

        switch($type) {
            case self::JPG:
                $extension = "jpg";
            break;

            case self::PNG:
                $extension = "png";
            break;

            default:
                $extension = "und";
            break;
        }

        return $extension;
    }
}

?>