<?php

session_start();

if(isset($_SESSION['user'])) {
	$U = new User($_SESSION['user']['id'], $_SESSION['user']['login'], $_SESSION['user']['email']);
}

define('ITEMS_TABLE', 'eagle_items');
define('CATEGORIES_TABLE', 'eagle_categories');
define('USERS_TABLE', 'eagle_users');

define('DB_HOST', 'localhost');
define('DB_NAME', 'dbname');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');

define('ROOT', $_SERVER['DOCUMENT_ROOT']);

define('TEMPLATES_DIR', 'templates');
define('TEMPLATE_EXTENSION', 'tpl');

?>