<?php

class TwigTemplateManager {
    public $filename;

    private $templates = [];
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function addTemplate($pattern, $replace) {
        $this->templates[$pattern] = $replace;
    }

    public function get() {
        return $this->twig->render($this->filename, $this->templates);
    }
}

?>