<?php

class TextEditor extends FormField {
    public $title;

    public function __construct($title) {
        $this->title = $title;
    }

    public function get(Twig_Environment $twig) {
        return $twig->render('text-editor.tpl', ['id' => $this->id, 'title' => $this->title, 'value' => $this->value]);
    }
}

?>