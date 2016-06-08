<?php

// user class voor login

include_once("login.db.php");

class User {
	
	private $database;

	// constructor
	public function __construct() {
		$this->database = new ConnectDB();
		$this->database = $this->database->connect();
	}

	// login class met binding
	public function Login($username, $password) {
		if (!empty($username) && !empty($password))  {
			$stmt = $this->database->prepare("SELECT * FROM `evdnl_blog_auth_yc` WHERE username=? AND pass=?");
			$stmt->bindParam(1, $username);
			$stmt->bindParam(2, $password);
			$stmt->execute();

			if($stmt->rowCount() == 1) {
				header('location: ../admin/loginok.php');
			} else {
				echo "Verkeerde gebruikersnaam of wachtwoord";
			}
		} else {
			echo "Invoer is leeg";
		} 
	}
}

?>