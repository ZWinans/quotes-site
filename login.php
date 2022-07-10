<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<!-- Zach Winans -->
<body>

<?php
session_start();

if (isset($_GET['failure'])) {

    if ($_GET['failure'] === "blank") {

        echo '<div class="error"><h2>Please enter a valid <br> username/password</h2></div>';
    }

    if ($_GET['failure'] === "true") {

        echo '<div class="error"><h2>Username/Password incorrect</h2></div>';
    }
}

?>

<h2>Login</h2>
	<div class="registerContainer">


		<form id="loginForm" method="post" action="controller.php">

			<input type="text" id="user" name="userLogin" placeholder="Username" required>

			<br> <br> <input type="password" id="password" name="passwordLogin"
				placeholder="Password" required> <br> <br> <input type="submit"
				value="Submit">
			<div class="error"></div>
		</form>




	</div>
</body>