<html>
<head>
	<meta charset="utf-8">
	<title>Admin login</title>
	<link rel="stylesheet" href="../css/admin-style.css">
	<style>
	html {
		background-color: #f5f5f5;
	}
	input {
		display: block;
		margin-bottom: 10px;
	}
	</style>
</head>

<body>
</body>
</html>
<?php
//include("menu.php");

// login
require_once("../classes/config/login.user.php");

//
if (isset($_POST['submituser'])) {
	$username = $_POST['username'];
	$pass = $_POST['password'];

	$newuser = new User();
	$newuser->Login($username, $pass);
}

?>

<body>
	<form action="#" method="post">
		Gebruikersnaam: <input type="text" name="username">
		Wachtwoord: <input type="password" name="password">
		<input type="submit" name="submituser" value="Inloggen">
	</form>
</body>
<html>
</html>