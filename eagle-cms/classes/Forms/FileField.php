<?php

class FileField extends FormField {
    private $file;

    public function __construct(File $file) {
        $this->file = $file;
    }

	public function get(Twig_Environment $twig) {
		return $twig->render('filefield.tpl', ['id' => $this->id, 'file' => $this->file->getContents(), 'time' => time()]);
	}
}

?>