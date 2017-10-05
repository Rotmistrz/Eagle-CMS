<?php

class TemplateManager {
    private $path;
    private $templates = [];

    public function __construct($path) {
        $this->path = $path;
    }

    public function addTemplate($pattern, $replace) {
        $this->templates[$pattern] = $replace;
    }

    public function transformString($str) {
        $content = $str;

        foreach($this->templates as $pattern => $value) {
            $content = str_replace("{{ " . $pattern . " }}", $value, $content);
        }

        return $content;
    }

    public function transformFile($filename) {
        $content = file_get_contents($this->path . "/" . $filename);

        return $this->transformString($content);
    }
}

?>