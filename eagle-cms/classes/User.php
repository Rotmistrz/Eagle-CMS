<?php

class User {
	private $id;
	private $login;
	private $email;

	public function __construct($id, $login, $email) {
		$this->id = $id;
		$this->login = $login;
		$this->email = $email;
	}

	public function getId() {
		return $this->id;
	}

	public function getLogin() {
		return $this->login;
	}

	public function getEmail() {
		return $this->email;
	}

	public static function register($login, $email, $password) {
		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare("SELECT id, login, email FROM " . USERS_TABLE . " WHERE login = :login");
		$loading->bindValue(':login', $login, PDO::PARAM_STR);
		$loading->execute();

		if($loading->rowCount()) {
			throw new UserRegisterException("Użytkownik o takim loginie już istnieje.");
		}

		$loading = $pdo->prepare("SELECT id, login, email FROM " . USERS_TABLE . " WHERE email = :email");
		$loading->bindValue(':email', $email, PDO::PARAM_STR);
		$loading->execute();

		if($loading->rowCount()) {
			throw new UserRegisterException("Użytkownik o takim adresie e-mail już istnieje.");
		}

		$password = password_hash($password, PASSWORD_BCRYPT);

		$inserting = $pdo->prepare("INSERT INTO " . USERS_TABLE . " VALUES(NULL, :login, :email, :password)");
		$inserting->bindValue(':login', $login, PDO::PARAM_STR);
		$inserting->bindValue(':email', $email, PDO::PARAM_STR);
		$inserting->bindValue(':password', $password, PDO::PARAM_STR);

		if($inserting->execute()) {
			return true;
		} else {
			return false;
		}
	}

	public static function login($login, $password) {
		$pdo = DataBase::getInstance();
		$loading = $pdo->prepare("SELECT * FROM " . USERS_TABLE . " WHERE login = :login");
		$loading->bindValue(':login', $login, PDO::PARAM_STR);
		$loading->execute();

		if($result = $loading->fetch()) {
			if(password_verify($password, $result['password'])) {
				$user = new self($result['id'], $result['login'], $result['email']);
				$_SESSION['user'] = ['id' => $result['id'], 'login' => $result['login'], 'email' => $result['email']];

				return $user;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}

?>