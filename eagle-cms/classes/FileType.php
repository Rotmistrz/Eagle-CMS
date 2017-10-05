<?php

class FileType {
    const JPG = "image/jpeg";
    const PNG = "image/png";
    const GIF = "image/gif";

    const UNDEFINED = "undefined";

    public static function getExtension($type) {
        $extension;

        switch($type) {
            case self::JPG:
                $extension = "jpg";
            break;

            case self::PNG:
                $extension = "png";
            break;

            case self::GIF:
                $extension = "gif";
            break;

            default:
                $extension = self::UNDEFINED;
            break;
        }

        return $extension;
    }
}

?>