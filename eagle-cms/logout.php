<?php

session_start();

require 'vendor/autoload.php';
try {
	if($U = User::getInstance()) {
		if($U->logout()) {
			session_start();
			InformationManager::set(new Information(Information::CORRECT, "Nastąpiło poprawne wylogowanie."));
		} else {
			InformationManager::set(new Information(Information::ERROR, "Wystąpiły problemy podczas wylogowywania. Spróbuj ponownie później."));
		}
	} else {
		InformationManager::set(new Information(Information::ERROR, "Aby się wylogować, musisz się uprzednio zalogować."));
	}	
} catch(Exception $e) {
	InformationManager::set(new Information(Information::ERROR, $e->getMessage()));
}

header("Location: index.php");

?>