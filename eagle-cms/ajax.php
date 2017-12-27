<?php

require 'eagle-dependencies.php';

$debug = false;

if($debug) {
    $item_id = (isset($_GET['item_id'])) ? $_GET['item_id'] : 0;
    $id = (isset($_GET['id'])) ? $_GET['id'] : $_POST['id'];
    $parent_id = (isset($_GET['parentId'])) ? $_GET['parentId'] : $_POST['parent_id'];
    $module = (isset($_GET['module'])) ? $_GET['module'] : $_POST['module'];
    $operation = (isset($_GET['operation'])) ? $_GET['operation'] : $_POST['operation'];
    $type = (isset($_GET['type'])) ? $_GET['type'] : $_POST['type'];
} else {
    $module = $_POST['module'];
    $operation = $_POST['operation'];

    $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $parent_id = (isset($_POST['parent_id'])) ? $_POST['parent_id'] : 0;
    $type = (isset($_POST['type'])) ? $_POST['type'] : 0;

    $item_id = (isset($_POST['item_id'])) ? $_POST['item_id'] : 0;

    $code = (isset($_POST['code'])) ? trim($_POST['code']) : "";
    $slug = (isset($_POST['slug'])) ? trim($_POST['slug']) : "";
}

$json = [];
$json['module'] = $module;
$json['operation'] = $operation;
$json['error'] = false;

if($module == Modules::ITEM) {
    $json['item'] = [];
    $json['item']['id'] = $id;
    $json['item']['type'] = $type;
    $json['item']['parent_id'] = $parent_id;
} else if($module == Modules::GALLERY_PICTURE) {
    $json['picture'] = [];
    $json['picture']['id'] = $id;
    $json['picture']['item_id'] = $item_id;
} else if($module == Modules::PAGE) {
    $json['page'] = [];
    $json['page']['id'] = $id;
    $json['page']['slug'] = $slug;
} else if($module == Modules::DATA_DEFINED) {
    $json['dataDefined'] = [];
    $json['dataDefined']['id'] = $id;
    $json['dataDefined']['code'] = $code;
}

$errorMessage = "";
$errorOccured = false;

try {
    $loader = new Twig_Loader_Filesystem(TEMPLATES_DIR);
    $twig = new Twig_Environment($loader, array('autoescape' => false));

    if($module == Modules::ITEM) {
        if($operation == 'edit' || $operation == 'add') {
            header('Content-type: application/json');

            if($operation == 'edit') {
                $item = Item::load($id);
                $correctMessage = "Poprawnie edytowano element.";
                $baseErrorMessage = "Wystąpiły problemy podczas edycji elementu.";
            } else {
                $item = Item::create($type);
                $correctMessage = "Poprawnie dodano element.";
                $baseErrorMessage = "Wystąpiły problemy podczas dodawania elementu.";
            }

            if(isset($_POST['category'])) {
                $categories = new CategoriesList($_POST['category']);
            } else {
                $categories = new NoCategory();
            }

            $item->setCategories($categories);
            $item->parentId = $parent_id;

            $fields = Item::getFields();
            $languages = Item::getLanguages();
            $value;

            foreach($languages as $lang) {
                foreach($fields as $field) {
                    if(isset($_POST[Item::getDatabaseFieldname($field, $lang)])) {
                        $value = $_POST[Item::getDatabaseFieldname($field, $lang)];

                        $json['item'][Item::getDatabaseFieldname($field, $lang)] = $value;
                    } else {
                        $value = null;
                    }

                    $item->setContent($lang, $field, $value);
                }
            }

            $FileUploader = new FileUploader(ROOT . '/' . ITEMS_DIR . '/');

            if($type == 1) {
                $FileUploader->addFile('file_1', '1/' . $item->getId(), array(FileType::JPG), 1000000);
            }

            $errorOccured = false;

            if(!$item->save()) {
                $errorOccured = true;
                $errorMessage .= $baseErrorMessage;
            }

            if(!$FileUploader->upload()) {
                $errorOccured = true;
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
                $json['message'] = $correctMessage;

                $json['item']['row'] = $twig->render('table-item-1.tpl', ['item' => $item->getContentsByLanguage(Language::PL)]);
            } else {
                $json['error'] = true;
                $json['message'] = $errorMessage;
            }

            echo json_encode($json);
        } else if($operation == 'prepare-edit' || $operation == 'prepare-add') {
            header('Content-type: application/json');

            if($operation == 'prepare-edit') {
                $item = Item::load($id);
                $operation = "edit";
            } else {
                $item = new Item(null, $type, 0);
                $operation = "add";
            }

            $FormManager = new FormManager($twig);
            $FormManager->id = "item-edit";
            $FormManager->action = "";
            $FormManager->method = "post";
            $FormManager->class = "form request-form";
            $FormManager->addInputHidden('id', $id);
            $FormManager->addInputHidden('module', $module);
            $FormManager->addInputHidden('operation', $operation);
            $FormManager->addInputHidden('type', $type);
            $FormManager->addInputHidden('parent_id', $parent_id);

            if($type == 1) {
                $FormManager->addInput(Item::getDatabaseFieldname(Item::HEADER_1, Language::PL), 'Nagłówek 1', $item->getContent(Language::PL, Item::HEADER_1));
                $FormManager->addInput(Item::getDatabaseFieldname(Item::HEADER_2, Language::PL), 'Nagłówek 2', $item->getContent(Language::PL, Item::HEADER_2));
                $FormManager->addTextEditor(Item::getDatabaseFieldname(Item::CONTENT_1, Language::PL), 'Treść 1', $item->getContent(Language::PL, Item::CONTENT_1));
                $FormManager->addTextEditor(Item::getDatabaseFieldname(Item::CONTENT_2, Language::PL), 'Treść 2', $item->getContent(Language::PL, Item::CONTENT_2));

                $FormManager->addCategories(1, $item->getCategoriesArray());

                $FormManager->addFileField('file_1', 'Obrazek główny', new File(FileType::JPG, '/' . ITEMS_DIR . '/1', $item->getId()));
            }

            if($type == 2) {
                $FormManager->addInput(Item::getDatabaseFieldname(Item::HEADER_1, Language::PL), 'Tytuł 1', $item->getContent(Language::PL, Item::HEADER_1));
                $FormManager->addInput(Item::getDatabaseFieldname(Item::HEADER_2, Language::PL), 'Tytuł 2', $item->getContent(Language::PL, Item::HEADER_2));
            }

            if($type == 3) {
                $FormManager->addInput(Item::getDatabaseFieldname(Item::HEADER_1, Language::PL), 'Nagłówek', $item->getContent(Language::PL, Item::HEADER_1));
                $FormManager->addTextarea(Item::getDatabaseFieldname(Item::CONTENT_1, Language::PL), 'Treść', $item->getContent(Language::PL, Item::CONTENT_1));
            }

            $FormManager->addButton('Zatwierdź');

            $json['html'] = $FormManager->get();

            echo json_encode($json);
        } else if($operation == 'delete') {
            header('Content-type: application/json');

            $item = Item::load($id);

            if($item->delete()) {
                $json['message'] = "Poprawnie usunięto element.";
            } else {
                $json['error'] = true;
                $json['message'] = "Wystąpiły problemy podczas usuwania elementu.";
            }

            echo json_encode($json);
        } else if($operation == 'prepare-delete') {
            header('Content-type: application/json');
                $ChoiceForm = new ChoiceForm($twig);
                $ChoiceForm->id = "delete-form";
                $ChoiceForm->action = "/ajax.php";
                $ChoiceForm->title = "Czy na pewno chcesz usunąć ten element?";
                $ChoiceForm->back = "";

                $json['html'] = $ChoiceForm->get();
            echo json_encode($json);
        } else if($operation == 'hide') {
            header('Content-type: application/json');

            $item = Item::load($id);

            if($item->hide()) {
                $json['message'] = "Poprawnie ukryto element.";
            } else {
                $json['error'] = true;
                $json['message'] = "Wystąpiły problemy podczas ukrywania elementu.";
            }

            echo json_encode($json);
        } else if($operation == 'show') {
            header('Content-type: application/json');

            $item = Item::load($id);

            if($item->show()) {
                $json['message'] = "Poprawnie uwidoczniono element.";
            } else {
                $json['error'] = true;
                $json['message'] = "Wystąpiły problemy podczas uwidaczniania elementu.";
            }

            echo json_encode($json);
        } else if($operation == 'item-up') {
            header('Content-type: application/json');
            
            $current = Item::load($id);

            $earlier = $current->getEarlierOne();

            if(get_class($earlier) == "NoSuchItem") {
                $json['message'] = "Element jest już pierwszy w kolejności.";
            } else {
                $tmp = $current->order;
                $current->order = $earlier->order;
                $earlier->order = $tmp;

                if($current->save() && $earlier->save()) {
                    $json['item']['earlier'] = $earlier->getId();
                    $json['message'] = "Poprawnie zmieniono kolejność.";
                } else {
                    $json['error'] = true;
                    $json['message'] = "Wystąpiły problemy podczas zmiany kolejności elementów.";
                }
            }

            echo json_encode($json);
        } else if($operation == 'item-down') {
            header('Content-type: application/json');

            $current = Item::load($id);

            $later = $current->getLaterOne();

            if(get_class($later) == 'NoSuchItem') {
                $json['message'] = "Element jest już ostatni w kolejności.";
            } else {
                $tmp = $current->order;
                $current->order = $later->order;
                $later->order = $tmp;

                if($current->save() && $later->save()) {
                    $json['item']['later'] = $later->getId();
                    $json['message'] = "Poprawnie zmieniono kolejność.";
                } else {
                    $json['error'] = true;
                    $json['message'] = "Wystąpiły problemy podczas zmiany kolejności elementów.";
                }
            }

            echo json_encode($json);
        }
    } else if($module == Modules::GALLERY_PICTURE) {
        if($operation == 'prepare-add' || $operation == 'prepare-edit') {
            header('Content-type: application/json');

            if($operation == 'prepare-edit') {
                $picture = GalleryPicture::load($id);
                $nextOperation = "edit";
            } else {
                $picture = new GalleryPicture(null, $item_id, FileType::JPG, 0);
                $nextOperation = "add";
            }

            $FormManager = new FormManager($twig);
            $FormManager->id = "upload-gallery-picture";
            $FormManager->action = "";
            $FormManager->method = "post";
            $FormManager->class = "form request-form";
            $FormManager->addInputHidden('id', $picture->getId());
            $FormManager->addInputHidden('module', $module);
            $FormManager->addInputHidden('operation', $nextOperation);
            $FormManager->addInputHidden('item_id', $picture->itemId);

            $FormManager->addInput(GalleryPicture::getDatabaseFieldname(GalleryPicture::TITLE, Language::PL), 'Tytuł', $picture->getContent(Language::PL, GalleryPicture::TITLE));
            $FormManager->addTextarea(GalleryPicture::getDatabaseFieldname(GalleryPicture::DESCRIPTION, Language::PL), 'Opis', $picture->getContent(Language::PL, GalleryPicture::DESCRIPTION));

            $FormManager->addFileField('gallery-picture', 'Obrazek główny', new File($picture->getType(), '/' . GALLERIES_DIR, $picture->getId()));

            $FormManager->addButton('Zatwierdź');

            $json['html'] = $FormManager->get();

            echo json_encode($json);
        } else if($operation == 'edit' || $operation == 'add') {
            header('Content-type: application/json');

            if(!isset($_FILES['gallery-picture'])) {
                throw new Exception("Proszę załączyć obrazek.");
            }

            if($operation == 'edit') {
                $picture = GalleryPicture::load($id);

                $correctMessage = "Poprawnie edytowano zdjęcie.";
                $errorMessage = "Wystąpiły problemy podczas edycji zdjęcia.";
            } else {
                $picture = GalleryPicture::create($item_id);

                $correctMessage = "Poprawnie dodano zdjęcie.";
                $errorMessage = "Wystąpiły problemy podczas dodawania zdjęcia.";
            }

            $fields = GalleryPicture::getFields();
            $languages = GalleryPicture::getLanguages();
            $value;

            foreach($languages as $lang) {
                foreach($fields as $field) {
                    if(isset($_POST[Item::getDatabaseFieldname($field, $lang)])) {
                        $value = $_POST[Item::getDatabaseFieldname($field, $lang)];

                        $json['picture'][Item::getDatabaseFieldname($field, $lang)] = $value;
                    } else {
                        $value = null;
                    }

                    $picture->setContent($lang, $field, $value);
                }
            }

            try {
               if($picture->save()) {
                    $json['message'] = $correctMessage;

                    $json['picture']['row'] = $twig->render('manage-gallery-item.tpl', ['time' => time(), 'picture' => $picture->getContentsByLanguage(Language::PL)]);
                } else {
                    $json['error'] = true;
                    $json['message'] = $errorMessage;
                } 
            } catch(Exception $E) {
                $json['error'] = true;
                $json['message'] = $E->getMessage();
            }

            echo json_encode($json);
        } else if($operation == 'prepare-delete') {
            header('Content-type: application/json');
                $ChoiceForm = new ChoiceForm($twig);
                $ChoiceForm->id = "delete-form";
                $ChoiceForm->action = "/ajax.php";
                $ChoiceForm->title = "Czy na pewno chcesz usunąć to zdjęcie?";
                $ChoiceForm->back = "";

                $json['html'] = $ChoiceForm->get();
            echo json_encode($json);
        } else if($operation == 'delete') {
            header('Content-type: application/json');

            $picture = GalleryPicture::load($id);

            if($picture->delete()) {
                $json['message'] = "Poprawnie usunięto zdjęcie.";
            } else {
                $json['error'] = true;
                $json['message'] = "Wystąpiły problemy podczas usuwania zdjęcia.";
            }

            echo json_encode($json);
        } else if($operation == 'gallery-picture-up') {
            header('Content-type: application/json');
            
            $current = GalleryPicture::load($id);

            $earlier = $current->getEarlierOne();

            if(get_class($earlier) == "NoSuchGalleryPicture") {
                $json['message'] = "Zdjęcie jest już pierwsze w kolejności.";
            } else {
                $tmp = $current->order;
                $current->order = $earlier->order;
                $earlier->order = $tmp;

                if($current->save() && $earlier->save()) {
                    $json['picture']['earlier'] = $earlier->getId();
                    $json['message'] = "Poprawnie zmieniono kolejność.";
                } else {
                    $json['error'] = true;
                    $json['message'] = "Wystąpiły problemy podczas zmiany kolejności elementów.";
                }
            }

            echo json_encode($json);
        } else if($operation == 'gallery-picture-down') {
            header('Content-type: application/json');

            $current = GalleryPicture::load($id);

            $later = $current->getLaterOne();

            if(get_class($later) == 'NoSuchGalleryPicture') {
                $json['message'] = "Zdjęcie jest już ostatnie w kolejności.";
            } else {
                $tmp = $current->order;
                $current->order = $later->order;
                $later->order = $tmp;

                if($current->save() && $later->save()) {
                    $json['picture']['later'] = $later->getId();
                    $json['message'] = "Poprawnie zmieniono kolejność.";
                } else {
                    $json['error'] = true;
                    $json['message'] = "Wystąpiły problemy podczas zmiany kolejności elementów.";
                }
            }

            echo json_encode($json);
        }
    } else if($module == Modules::PAGE) {
        if($operation == 'edit' || $operation == 'add') {
            header('Content-type: application/json');

            if(Page::slugExists($slug, $id)) {
                $errorOccured = true;
                $errorMessage .= "Taki slug juz istnieje.";
            } else if(!Page::isSlugCorrect($slug)) {
                $errorOccured = true;
                $errorMessage .= "Niepoprawny slug.";
            } else {
                if($operation == 'edit') {
                    $page = Page::load($id);
                    $correctMessage = "Poprawnie edytowano stronę.";
                    $baseErrorMessage = "Wystąpiły problemy podczas edycji strony.";
                } else {
                    $page = Page::create($slug);
                    $correctMessage = "Poprawnie dodano stronę.";
                    $baseErrorMessage = "Wystąpiły problemy podczas dodawania strony.";
                }

                $page->setSlug($slug);

                $fields = Page::getFields();
                $languages = Page::getLanguages();
                $value;

                foreach($languages as $lang) {
                    foreach($fields as $field) {
                        if(isset($_POST[Page::getDatabaseFieldname($field, $lang)])) {
                            $value = $_POST[Page::getDatabaseFieldname($field, $lang)];

                            $json['page'][Page::getDatabaseFieldname($field, $lang)] = $value;
                        } else {
                            $value = null;
                        }

                        $page->setContent($lang, $field, $value);
                    }
                }

                try {
                    if(!$page->save()) {
                        $errorOccured = true;
                        $errorMessage .= $baseErrorMessage;
                    }
                } catch(Exception $e) {
                    $errorOccured = true;
                    $errorMessage .= $e->getMessage();
                }
            }

            if(!$errorOccured) {
                $json['message'] = $correctMessage;

                $json['page']['row'] = $twig->render('table-page-1.tpl', ['page' => $page->getContentsByLanguage(Language::PL)]);
            } else {
                $json['error'] = true;
                $json['message'] = $errorMessage;
            }

            echo json_encode($json);
        } else if($operation == 'prepare-edit' || $operation == 'prepare-add') {
            header('Content-type: application/json');

            if($operation == 'prepare-edit') {
                $page = Page::load($id);
                $operation = "edit";
            } else {
                $page = new Page(null, null);
                $operation = "add";
            }

            $FormManager = new FormManager($twig);
            $FormManager->id = "page-edit";
            $FormManager->action = "";
            $FormManager->method = "post";
            $FormManager->class = "form request-form";
            $FormManager->addInputHidden('id', $id);
            $FormManager->addInputHidden('module', $module);
            $FormManager->addInputHidden('operation', $operation);

            $FormManager->addInput('slug', 'Slug', $page->getSlug());
            $FormManager->addInput(Page::getDatabaseFieldname(Page::TITLE, Language::PL), 'Tytuł', $page->getContent(Language::PL, Page::TITLE));
            $FormManager->addTextarea(Page::getDatabaseFieldname(Page::DESCRIPTION, Language::PL), 'Opis', $page->getContent(Language::PL, Page::DESCRIPTION));

            $FormManager->addButton('Zatwierdź');

            $json['html'] = $FormManager->get();

            echo json_encode($json);
        } else if($operation == 'delete') {
            header('Content-type: application/json');

            $page = Page::load($id);

            if($page->delete()) {
                $json['message'] = "Poprawnie usunięto stronę.";
            } else {
                $json['error'] = true;
                $json['message'] = "Wystąpiły problemy podczas usuwania strony.";
            }

            echo json_encode($json);
        } else if($operation == 'prepare-delete') {
            header('Content-type: application/json');
                $ChoiceForm = new ChoiceForm($twig);
                $ChoiceForm->id = "delete-form";
                $ChoiceForm->action = "/ajax.php";
                $ChoiceForm->title = "Czy na pewno chcesz usunąć tę stronę?";
                $ChoiceForm->back = "";

                $json['html'] = $ChoiceForm->get();
            echo json_encode($json);
        }
    } else if($module == Modules::DATA_DEFINED) {
        if($operation == 'prepare-edit' || $operation == 'prepare-add') {
            header('Content-type: application/json');

            if($operation == 'prepare-edit') {
                $dataDefined = DataDefined::load($id);
                $operation = "edit";
            } else {
                $dataDefined = new DataDefined(null, null);
                $operation = "add";
            }

            $FormManager = new FormManager($twig);
            $FormManager->id = "data-defined-edit";
            $FormManager->action = "";
            $FormManager->method = "post";
            $FormManager->class = "form request-form";
            $FormManager->addInputHidden('id', $id);
            $FormManager->addInputHidden('module', $module);
            $FormManager->addInputHidden('operation', $operation);

            $FormManager->addInput('code', 'Kod', $dataDefined->getCode());
            $FormManager->addInput(DataDefined::getDatabaseFieldname(DataDefined::VALUE, Language::PL), 'Wartość', $dataDefined->getValue(Language::PL));

            $FormManager->addButton('Zatwierdź');

            $json['html'] = $FormManager->get();

            echo json_encode($json);
        } else if($operation == 'edit' || $operation == 'add') {
            header('Content-type: application/json');

            if(empty($code)) {
                $errorOccured = true;
                $errorMessage .= "Proszę podać kod danej zdefiniowanej.";
            } else if(!DataDefined::isCodeCorrect($code)) {
                $errorOccured = true;
                $errorMessage .= "Kod danej zdefiniowanej jest niepoprawny.";
            } else if(DataDefined::codeExists($code, $id)) {
                $errorOccured = true;
                $errorMessage .= "Taki kod już istnieje.";
            } else {
                if($operation == 'edit') {
                    $dataDefined = DataDefined::load($id);
                    $correctMessage = "Poprawnie edytowano daną zdefiniowaną.";
                    $baseErrorMessage = "Wystąpiły problemy podczas edycji danej zdefiniowanej.";
                } else {
                    $dataDefined = DataDefined::create($code);
                    $correctMessage = "Poprawnie dodano daną zdefiniowaną.";
                    $baseErrorMessage = "Wystąpiły problemy podczas dodawania danej zdefiniowanej.";
                }

                $fields = DataDefined::getFields();
                $languages = DataDefined::getLanguages();
                $value;

                foreach($languages as $lang) {
                    foreach($fields as $field) {
                        if(isset($_POST[DataDefined::getDatabaseFieldname($field, $lang)])) {
                            $value = $_POST[DataDefined::getDatabaseFieldname($field, $lang)];

                            $json['dataDefined'][DataDefined::getDatabaseFieldname($field, $lang)] = $value;
                        } else {
                            $value = null;
                        }

                        $dataDefined->setContent($lang, $field, $value);
                    }
                }

                try {
                    if(!$dataDefined->save()) {
                        $errorOccured = true;
                        $errorMessage .= $baseErrorMessage;
                    }
                } catch(Exception $e) {
                    $errorOccured = true;
                    $errorMessage .= $e->getMessage();
                }
                
            }

            if(!$errorOccured) {
                $json['message'] = $correctMessage;

                $json['dataDefined']['row'] = $twig->render('table-data-defined-1.tpl', ['data' => $dataDefined->getContentsByLanguage(Language::PL)]);
            } else {
                $json['error'] = true;
                $json['message'] = $errorMessage;
            }

            echo json_encode($json);
        } else if($operation == 'delete') {
            header('Content-type: application/json');

            $dataDefined = DataDefined::load($id);

            if($dataDefined->delete()) {
                $json['message'] = "Poprawnie usunięto daną zdefiniowaną.";
            } else {
                $json['error'] = true;
                $json['message'] = "Wystąpiły problemy podczas usuwania danej zdefiniowanej.";
            }

            echo json_encode($json);
        } else if($operation == 'prepare-delete') {
            header('Content-type: application/json');
                $ChoiceForm = new ChoiceForm($twig);
                $ChoiceForm->id = "delete-form";
                $ChoiceForm->action = "/ajax.php";
                $ChoiceForm->title = "Czy na pewno chcesz usunąć tę daną zdefiniowaną?";
                $ChoiceForm->back = "";

                $json['html'] = $ChoiceForm->get();
            echo json_encode($json);
        }
    }
} catch(Exception $E) {
    header('Content-type: application/json');

    $json['error'] = true;
    $json['message'] = "Wystąpiły problemy techniczne. Proszę spróbować ponownie za chwilę." . $E->getMessage();

    echo json_encode($json);
}

?>