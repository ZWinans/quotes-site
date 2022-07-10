<!DOCTYPE html>
<html>
<head>
<title>Add Quote</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<!-- Zach Winans -->

<?php 
if (isset($_GET['failure'])) {
    if ($_GET['failure'] === "true") {
        echo '<div class="error"><h2>Please enter a valid quote</h2></div>';
    }
}

?>

<h2>Add a quote</h2>
<div class="addQuoteContainer">


	<form id="form" method="post" action="controller.php">

		<textarea id="quote" name="quote" rows="4" cols="50" placeholder="Enter your quote here" required></textarea>

		<br> <br> 
		<input type="text" id="author" name="author" placeholder="Author" required> 
		<br> <br>
		<input type="submit" value="Add Quote">

	</form>



</div>


<?php

?>