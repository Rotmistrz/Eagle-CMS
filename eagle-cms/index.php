<?php

require 'vendor/autoload.php';

try {
	$loader = new Twig_Loader_Filesystem(TEMPLATES_DIR);
	$twig = new Twig_Environment($loader, array('autoescape' => false));

	$TemplateManager = new TemplateManager($twig);
	$TemplateManager->filename = 'index.tpl';

	$module = (isset($_GET['module'])) ? $_GET['module'] : null;
	$operation = (isset($_GET['operation'])) ? $_GET['operation'] : null;

	$id = (isset($_GET['id'])) ? $_GET['id'] : null;
	$type = (isset($_GET['type'])) ? $_GET['type'] : null;

	$content = '';
	$correctMessage = '';
	$errorMessage = '';
	$errorOccured = 0;

	$ContentManager = new ContentManager();
	$ContentManager->lang = Language::PL;
	$ContentManager->twig = $twig;

	if($information = InformationManager::get()) {
		if($information->type == Information::CORRECT) {
			$content .= $ContentManager->getCorrectMessage($information->content);
		} else if($information->type == Information::ERROR) {
			$content .= $ContentManager->getErrorMessage($information->content);
		}

		InformationManager::clear();
	}

	if($module == 'pages') {
		$FormManager = new FormManager($twig);
		$FormManager->id = "select-new-element-form";
		$FormManager->class = "form";
		$FormManager->title = "Dodaj nowy element";
		$FormManager->action = "index.php";
		$FormManager->method = "get";

		$select = new Select();
		$select->id = "type";
		$select->addOption(1, "typ 1");
		$select->addOption(2, "typ 2");
		$select->addOption(3, "typ 3");

		$FormManager->addInputHidden('module', 'item');
		$FormManager->addInputHidden('operation', 'add');
		$FormManager->addSelect('Wybierz typ', $select);
		$FormManager->addButton('Zatwierdź');
		$content .= $FormManager->get();

		$ContentManager->template = 'table-items-1.tpl';

		$content .= ContentManager::getTitle("Sekcja 1");
		$content .= $ContentManager->getAllItemsByType(1);

		$content .= ContentManager::getTitle("Sekcja 2");
		$content .= $ContentManager->getAllItemsByType(2);

	} 

	else if($module == 'item') {
		if($operation == 'edit' || $operation == 'add') {
			$correctMessage;
			$baseErrorMessage;
			$item;

			if($operation == 'edit') {
				$item = Item::load($id);
				$correctMessage = "Poprawnie edytowano element.";
				$baseErrorMessage = "Wystąpiły problemy podczas edycji elementu.";
			} else if($operation == 'add') {
				$item = new Item(null, $type, 0);
				$correctMessage = "Poprawnie dodano element.";
				$baseErrorMessage = "Wystąpiły problemy podczas dodawania elementu.";
			}

			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				if($operation == 'add') {
					$item = Item::create($type);
				}

				$fields = Item::getFields();
				$languages = Item::getLanguages();
				$value;

				foreach($languages as $lang) {
					foreach($fields as $field) {
						if(isset($_POST[Item::getDatabaseFieldname($field, $lang)])) {
							$value = $_POST[Item::getDatabaseFieldname($field, $lang)];
						} else {
							$value = null;
						}

						$item->setContent($lang, $field, $value);
					}
				}

				$FileUploader = new FileUploader();
				$FileUploader->path = ROOT . "/uploads/";
				$FileUploader->addFile('file_1', '1/' . $item->id, 'jpg', 1000000);

				$errorOccured = 0;
				$errorMessage = '';

				if(!$item->save()) {
					$errorMessage .= $baseErrorMessage;
				}

				if(!$FileUploader->upload()) {
					$errorOccured = 1;
					$errors = $FileUploader->getErrors();

					$quantity = count($errors);

					if(strlen($errorMessage) > 0) {
						$errorMessage .= "<br />";
					}

					for($i = 0; $i < $quantity; $i++) {
						$errorMessage .= $errors[$i];

						if($i < $quantity - 1) {
							$errorMessage .= "<br />";
						}
					}
				}

				if(!$errorOccured) {
					InformationManager::set(new Information(Information::CORRECT, $correctMessage));
				} else {
					InformationManager::set(new Information(Information::ERROR, $errorMessage));
				}

				header('Location: index.php?module=pages');
			} else {
				$FormManager = new FormManager($twig);
				$FormManager->id = "item-edit";
				$FormManager->action = "index.php?module=" . $module . "&amp;operation=" . $operation . "&amp;type=" . $type . "&amp;id=" . $id;
				$FormManager->method = "post";
				$FormManager->class = "form";

				if($type == 1) {
					$FormManager->addInput(Item::getDatabaseFieldname(Item::HEADER_1, Language::PL), 'Nagłówek 1', $item->getContent(Language::PL, Item::HEADER_1));
					$FormManager->addInput(Item::getDatabaseFieldname(Item::HEADER_2, Language::PL), 'Nagłówek 2', $item->getContent(Language::PL, Item::HEADER_2));
					$FormManager->addTextarea(Item::getDatabaseFieldname(Item::CONTENT_1, Language::PL), 'Treść 1', $item->getContent(Language::PL, Item::CONTENT_1));
					$FormManager->addFileField('file_1', 'Obrazek główny');
				}

				$FormManager->addButton('Zatwierdź');
				$content .= $FormManager->get();
			}
		} else if($operation == 'delete') {
			$item = Item::load($id);

			if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accepted'] == 1) {
				$correctMessage = "Poprawnie usunięto element.";

				if(!$item->delete()) {
					$errorMessage = "Wystąpiły problemy podczas usuwania elementu.";
					$errorOccured = 1;
				}

				if(!$errorOccured) {
					InformationManager::set(new Information(Information::CORRECT, $correctMessage));
				} else {
					InformationManager::set(new Information(Information::ERROR, $errorMessage));
				}

				header('Location: index.php?module=pages');
			} else {
				$ChoiceForm = new ChoiceForm($twig);
				$ChoiceForm->id = "delete-form";
				$ChoiceForm->action = "index.php?module=" . $module . "&amp;operation=delete&amp;id=" . $id;
				$ChoiceForm->title = "Czy na pewno chcesz usunąć ten element?";
				$ChoiceForm->back = "index.php?module=pages";

				$content .= $ChoiceForm->get();
			}
		} else if($operation == 'item-up') {
			if(is_null($id)) {
				throw new Exception("Proszę podać id elementu.");
			}

			$current = Item::load($id);

			$earlier = $current->getEarlierOne();

			if(get_class($earlier) == 'NoItem') {
				InformationManager::set(new Information(Information::CORRECT, "Element jest już pierwszy w kolejności."));
			} else {
				$tmp = $current->order;
				$current->order = $earlier->order;
				$earlier->order = $tmp;

				if($current->save() && $earlier->save()) {
					InformationManager::set(new Information(Information::CORRECT, "Poprawnie zmieniono kolejność."));
				} else {
					InformationManager::set(new Information(Information::ERROR, "Wystąpiły problemy podczas zmiany kolejności elementów."));
				}
			}

			header('Location: index.php?module=pages');
		} else if($operation == 'item-down') {
			if(is_null($id)) {
				throw new Exception("Proszę podać id elementu.");
			}

			$current = Item::load($id);

			$later = $current->getLaterOne();

			if(get_class($later) == 'NoItem') {
				InformationManager::set(new Information(Information::CORRECT, "Element jest już ostatni w kolejności."));
			} else {
				$tmp = $current->order;
				$current->order = $later->order;
				$later->order = $tmp;

				if($current->save() && $later->save()) {
					InformationManager::set(new Information(Information::CORRECT, "Poprawnie zmieniono kolejność."));
				} else {
					InformationManager::set(new Information(Information::ERROR, "Wystąpiły problemy podczas zmiany kolejności elementów."));
				}
			}

			header('Location: index.php?module=pages');
		}
	} 

	else if($module == 'categories') {
		$FormManager = new FormManager($twig);
		$FormManager->id = "select-new-category-form";
		$FormManager->class = "form";
		$FormManager->title = "Dodaj nową kategorię";
		$FormManager->action = "index.php";
		$FormManager->method = "get";

		$select = new Select();
		$select->id = "type";
		$select->addOption(1, "typ 1");
		$select->addOption(2, "typ 2");
		$select->addOption(3, "typ 3");

		$FormManager->addInputHidden('module', 'category');
		$FormManager->addInputHidden('operation', 'add');
		$FormManager->addSelect('Wybierz typ', $select);
		$FormManager->addButton('Zatwierdź');
		$content .= $FormManager->get();

		$ContentManager->template = 'table-categories-1.tpl';

		$content .= ContentManager::getTitle("Sekcja 1");
		$content .= $ContentManager->getAllCategoriesByType(1);
	} 

	else if($module == 'category') {
		if($operation == 'edit' || $operation == 'add') {
			$correctMessage;
			$baseErrorMessage;
			$category;

			if($operation == 'edit') {
				$category = Category::load($id);
				$correctMessage = "Poprawnie edytowano kategorię.";
				$errorMessage = "Wystąpiły problemy podczas edycji kategorii.";
			} else if($operation == 'add') {
				$category = new Category(null, $type, 0);
				$correctMessage = "Poprawnie dodano kategorię.";
				$errorMessage = "Wystąpiły problemy podczas dodawania kategorii.";
			}

			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				if($operation == 'add') {
					$category = Category::create($type);
				}

				$fields = Category::getFields();
				$languages = Category::getLanguages();
				$value;

				foreach($languages as $lang) {
					foreach($fields as $field) {
						if(isset($_POST[Category::getDatabaseFieldname($field, $lang)])) {
							$value = $_POST[Category::getDatabaseFieldname($field, $lang)];
						} else {
							$value = null;
						}

						$category->setContent($lang, $field, $value);
					}
				}

				if($category->save()) {
					InformationManager::set(new Information(Information::CORRECT, $correctMessage));
				} else {
					InformationManager::set(new Information(Information::ERROR, $errorMessage));
				}

				header('Location: index.php?module=categories');
			} else {
				$FormManager = new FormManager($twig);
				$FormManager->id = "category-edit";
				$FormManager->action = "index.php?module=" . $module . "&amp;operation=" . $operation . "&amp;type=" . $type . "&amp;id=" . $id;
				$FormManager->method = "post";
				$FormManager->class = "form";

				if($type == 1) {
					$FormManager->addInput(Category::getDatabaseFieldname(Category::HEADER_1, Language::PL), 'Nagłówek', $category->getContent(Language::PL, Category::HEADER_1));
				}

				$FormManager->addButton('Zatwierdź');
				$content .= $FormManager->get();
			}
		}
	} else {
		$content .= ContentManager::getTitle('It works! Hello EagleCMS!');
	}
} catch(Exception $E) {
	//$content .= $ContentManager->getErrorMessage($E->getMessage());
	$content .= $E->getMessage();
}

$TemplateManager->addTemplate('title', 'EagleCMS');
$TemplateManager->addTemplate('path', '/eagle-cms');
$TemplateManager->addTemplate('content', $content);

echo $TemplateManager->get();

?>