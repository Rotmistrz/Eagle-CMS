<?php

require 'eagle-cms/vendor/autoload.php';

try {
	$loader = new Twig_Loader_Filesystem(TEMPLATES_DIR);
	$twig = new Twig_Environment($loader, array('autoescape' => false));

	$TemplateManager = new TemplateManager($twig);
	$TemplateManager->filename = 'index.tpl';

	$module = (isset($_GET['module'])) ? $_GET['module'] : null;
	$id = (isset($_GET['id'])) ? $_GET['id'] : null;
	$type = (isset($_GET['type'])) ? $_GET['type'] : null;

	$content = '';

	$ContentManager = new ContentManager();
	$ContentManager->lang = Language::PL;
	$ContentManager->twig = $twig;

	$ContentManager->template = 'items-1.tpl';
	$TemplateManager->addTemplate('items_1', $ContentManager->getAllItemsByType(1));

} catch(Exception $E) {
	$content .= $E->getMessage();
}

$TemplateManager->addTemplate('title', 'EagleCMS');
$TemplateManager->addTemplate('content', $content);

echo $TemplateManager->get();

?>