<?php

session_start();

require 'vendor/autoload.php';

try {
	$loader = new Twig_Loader_Filesystem(TEMPLATES_DIR);
	$twig = new Twig_Environment($loader, array('autoescape' => false));

	$TemplateManager = new TwigTemplateManager($twig);
	$TemplateManager->addTemplate('title', 'EagleCMS');
	$TemplateManager->addTemplate('path', '/eagle-cms');

	$ContentManager = new ContentManager($twig);
	$ContentManager->lang = Language::PL;
	$ContentManager->setLoadHiddenItems(true);

	$content = "";

	if($crosssideInformation = InformationManager::get()) {
		if($crosssideInformation->type == Information::CORRECT) {
			$information = $ContentManager->getCorrectMessage($crosssideInformation->content);
		} else if($crosssideInformation->type == Information::ERROR) {
			$information = $ContentManager->getErrorMessage($crosssideInformation->content);
		}

		$content .= $information;

		InformationManager::clear();
	}
} catch(Exception $e) {
	echo $ContentManager->getErrorMessage($e->getMessage());
	exit();
	// wyswietlenie strony błędu
}

if($U = User::getInstance()) {
	try {
		$TemplateManager->filename = 'index.tpl';

		$module = (isset($_GET['module'])) ? $_GET['module'] : null;
		$operation = (isset($_GET['operation'])) ? $_GET['operation'] : null;

		$id = (isset($_GET['id']) && !empty($_GET['id'])) ? $_GET['id'] : null;
		$type = (isset($_GET['type']) && !empty($_GET['type'])) ? $_GET['type'] : null;
		$parent_id = (isset($_GET['parent_id']) && !empty($_GET['parent_id'])) ? $_GET['parent_id'] : 0;

		if($type == null && $parent_id != 0) {
			$current = Item::load($parent_id);
			$type = $current->type;
		}

		$correctMessage = '';
		$errorMessage = '';
		$errorOccured = 0;

		$username = $U->getLogin();
		$TemplateManager->addTemplate('username', $username);

		if($module == 'pages') {
			$FormManager = new FormManager($twig);
			$FormManager->id = "select-new-element-form";
			$FormManager->class = "form request-form";
			$FormManager->title = "Dodaj nowy element";
			$FormManager->action = "index.php";
			$FormManager->method = "get";

			$select = new Select();
			$select->id = "type";
			$select->addOption(1, "typ 1");
			$select->addOption(2, "typ 2");
			$select->addOption(4, "typ 4");

			$FormManager->addInputHidden('id', 0);
			$FormManager->addInputHidden('module', 'item');
			$FormManager->addInputHidden('parent_id', 0);
			$FormManager->addInputHidden('operation', 'prepare-add');
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
			if($operation == 'showcase') {
				$content .= ContentManager::getTitle("Item " . $id);

				$FormManager = new FormManager($twig);
				$FormManager->id = "select-new-element-form";
				$FormManager->class = "form request-form";
				$FormManager->title = "Dodaj nowy element";
				$FormManager->action = "index.php";
				$FormManager->method = "get";

				$select = new Select();
				$select->id = "type";

				if($type == 1) {	
					$select->addOption(3, "typ 3");
				}

				$FormManager->addInputHidden('parent_id', $id);
				$FormManager->addInputHidden('module', 'item');
				$FormManager->addInputHidden('operation', 'prepare-add');
				$FormManager->addSelect('Wybierz typ', $select);
				$FormManager->addButton('Zatwierdź');
				$content .= $FormManager->get();

				$content .= $ContentManager->getGallery($id);


				$ContentManager->template = 'table-items-1.tpl';

				if($type == 1) {
					$content .= ContentManager::getTitle("Sekcja 3");
					$content .= $ContentManager->getAllItemsByParent($id, 3);
				}
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

			$content .= ContentManager::getTitle("Kategorie 1");
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
			} else if($operation == 'delete') {
				$category = Category::load($id);

				if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accepted'] == 1) {
					$correctMessage = "Poprawnie usunięto kategorię.";
					$errorMessage = "Wystąpiły problemy podczas usuwania kategorii.";

					if($category->delete()) {
						InformationManager::set(new Information(Information::CORRECT, $correctMessage));
					} else {
						InformationManager::set(new Information(Information::ERROR, $errorMessage));
					}

					header('Location: index.php?module=categories');
				} else {
					$ChoiceForm = new ChoiceForm($twig);
					$ChoiceForm->id = "delete-form";
					$ChoiceForm->action = "index.php?module=" . $module . "&amp;operation=delete&amp;id=" . $id;
					$ChoiceForm->title = "Czy na pewno chcesz usunąć tę kategorię?";
					$ChoiceForm->back = "index.php?module=categories";

					$content .= $ChoiceForm->get();
				}
			} else if($operation == 'category-up') {
				if(is_null($id)) {
					throw new Exception("Proszę podać id kategorii.");
				}

				$current = Category::load($id);

				$earlier = $current->getEarlierOne();

				if(get_class($earlier) == 'NoSuchCategory') {
					InformationManager::set(new Information(Information::CORRECT, "Kategoria jest już pierwsza w kolejności."));
				} else {
					$tmp = $current->order;
					$current->order = $earlier->order;
					$earlier->order = $tmp;

					if($current->save() && $earlier->save()) {
						InformationManager::set(new Information(Information::CORRECT, "Poprawnie zmieniono kolejność."));
					} else {
						InformationManager::set(new Information(Information::ERROR, "Wystąpiły problemy podczas zmiany kolejności kategorii."));
					}
				}

				header('Location: index.php?module=categories');
			} else if($operation == 'category-down') {
				if(is_null($id)) {
					throw new Exception("Proszę podać id kategorii.");
				}

				$current = Category::load($id);

				$later = $current->getLaterOne();

				if(get_class($later) == 'NoSuchCategory') {
					InformationManager::set(new Information(Information::CORRECT, "Kategoria jest już ostatnia w kolejności."));
				} else {
					$tmp = $current->order;
					$current->order = $later->order;
					$later->order = $tmp;

					if($current->save() && $later->save()) {
						InformationManager::set(new Information(Information::CORRECT, "Poprawnie zmieniono kolejność."));
					} else {
						InformationManager::set(new Information(Information::ERROR, "Wystąpiły problemy podczas zmiany kolejności kategorii."));
					}
				}

				header('Location: index.php?module=categories');
			}
		} else {
			$content .= ContentManager::getTitle('It works! Hello EagleCMS!');
		}
	} catch(Exception $E) {
		//$content .= $ContentManager->getErrorMessage($E->getMessage());
		$content .= $ContentManager->getErrorMessage($E->getMessage());
	}
} else {
	$TemplateManager->filename = 'login.tpl';
	
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(User::login($_POST['login'], $_POST['password'])) {
			InformationManager::set(new Information(Information::CORRECT, "Zalogowano poprawnie."));
		} else {
			InformationManager::set(new Information(Information::ERROR, "Niepoprawny login lub hasło."));
		}

		header("Location: index.php");
	}

	$FormManager = new FormManager($twig);
	$FormManager->id = "form-login";
	$FormManager->action = "index.php";
	$FormManager->method = "post";
	$FormManager->class = "form form-login";

	$FormManager->setTitle("Logowanie")->addInput('login', 'Login', '')->addInputPassword('password', 'Hasło', '');

	$FormManager->addButton('Zaloguj');
	$content .= $FormManager->get();
}

$TemplateManager->addTemplate('content', $content);

echo $TemplateManager->get();

?>