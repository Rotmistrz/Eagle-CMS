<?php

interface Viewable {
	public function getDefault(Twig_Environment $twig, $lang);
	public function getTable(Twig_Environment $twig, $lang);
}

?>