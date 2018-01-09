<?php

require 'eagle-cms/eagle-dependencies.php';

try {
	$loader = new Twig_Loader_Filesystem(TEMPLATES_DIR);
	$twig = new Twig_Environment($loader, array('autoescape' => false));

	$templateManager = new TemplateManager(TEMPLATES_DIR);

	$page = (isset($_GET['page'])) ? $_GET['page'] : null;
	$lang = (isset($_GET['lang'])) ? $_GET['lang'] : DEFAULT_LANG;

	define('LANG', $lang);

	if($pageData = Page::loadBySlug($page)) {
		$slug = $pageData->getSlug();
		$title = $pageData->getTitle(Language::PL);
	} else {
		$slug = "default";
		$title = "Eagle CMS";
	}

	$content = $slug;

	$contentManager = new ContentManager($twig);
	$contentManager->lang = LANG;

	$contentManager->template = 'items-1.tpl';
	$templateManager->addTemplate('items_1', $contentManager->getAllItemsByType(1));
	$templateManager->addTemplate('items_1_3', $contentManager->getAllItemsByParent(1, 3));
	$templateManager->addTemplate('selected_items_1_1_3', $contentManager->getItemsByType(1, 1, 3));

	$header = $templateManager->transformFile('header_main.tpl');
	$body = $templateManager->transformFile('body_main.tpl');

} catch(Exception $E) {
	$content .= $E->getMessage();
}



$templateManager->addTemplate('title', $title);
$templateManager->addTemplate('header', $header);
$templateManager->addTemplate('body', $body);
$templateManager->addTemplate('content', $content);

$result = $templateManager->transformFile('body.tpl');

$constantsCollection = DataDefinedCollection::load();

$constants = $constantsCollection->getItems();

foreach($constants as $data) {
	$templateManager->addTemplate("_" . $data->getCode() . "_", $data->getValue(LANG));
}

echo $templateManager->transformString($result);

?>