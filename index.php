<?php

require 'eagle-cms/vendor/autoload.php';

try {
	$loader = new Twig_Loader_Filesystem(TEMPLATES_DIR);
	$twig = new Twig_Environment($loader, array('autoescape' => false));

	$templateManager = new TemplateManager(TEMPLATES_DIR);

	$module = (isset($_GET['module'])) ? $_GET['module'] : null;
	$id = (isset($_GET['id'])) ? $_GET['id'] : null;
	$type = (isset($_GET['type'])) ? $_GET['type'] : null;

	$content = '';

	$contentManager = new ContentManager($twig);
	$contentManager->lang = Language::PL;

	$contentManager->template = 'items-1.tpl';
	$templateManager->addTemplate('items_1', $contentManager->getAllItemsByType(1));
	$templateManager->addTemplate('items_1_3', $contentManager->getAllItemsByParent(1, 3));

	$header = $templateManager->transformFile('header_main.tpl');
	$body = $templateManager->transformFile('body_main.tpl');

} catch(Exception $E) {
	$content .= $E->getMessage();
}

$templateManager->addTemplate('title', 'EagleCMS');
$templateManager->addTemplate('header', $header);
$templateManager->addTemplate('body', $body);

echo $templateManager->transformFile('body.tpl');

?>