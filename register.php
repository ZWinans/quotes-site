<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<!-- Zach Winans -->
<body>

<?php 
session_start();

if (isset($_GET['failure'])) {
    if ($_GET['failure'] === "true") {
        echo '<div class="error"><h2>That username is already <br> taken</h2></div>';
    }
    if ($_GET['failure'] === "blank") {
        
        echo '<div class="error"><h2>Please enter a valid <br> username/password</h2></div>';
    }
}



?>


<h2>Register</h2>
<div class="registerContainer">


<form id="regForm" method="post" action="controller.php">

<input type="text" id="user" name="user" placeholder="Username" required>

<br> <br>
<input type="password" id="password" name="password" placeholder="Password" required>
<br> <br>
<input type="submit" value="Submit">
</form>

<div class="error"></div>

<script>


</script>


</div>
</body>