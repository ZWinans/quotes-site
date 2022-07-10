<!-- 
This is the home page for Final Project, Quotation Service. 
It is a PHP file because later on you will add PHP code to this file.

File name quotes.php 
    
Author: Zach Winans
-->

<!DOCTYPE html>
<html>
<head>
<title>Quotation Service</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>



<body onload="showQuotes()">
	
	<h1>Quotation Service</h1>
	<br>
	

	<div id="quotes"></div>

	<script>
var element = document.getElementById("quotes");

// PURPOSE: AJAX call to controller.php
function showQuotes() {
    
	var ajax = new XMLHttpRequest();

	ajax.open("GET", "controller.php?todo=getQuotes", true);
	ajax.send();

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4 && ajax.status == 200) {
			
			string = ajax.responseText;
			
 			document.getElementById("quotes").innerHTML = string;

		}
	};



} // End function showQuotes
</script>

</body>
</html>