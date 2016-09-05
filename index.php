<?php
include "backend/db.php";
?>
<!DOCTYPE html>
<head>
	<title>Rubric Pro: Login</title>
	<link rel="stylesheet" href="css/login.css"> 
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet"> 
	<meta charset="UTF-8">
	<meta name="author" content="Aaron Walter (2016)">
	<meta name="description" content="Log in to Rubric Pro.">
</head>
<body>
	<div id="login">
		<img id="logo" src="images/logo.png" alt="Rubric Pro">
		<form method="post">
		  <h2>Username:</h2>
		  <input type="text" name="firstname" placeholder="rubricpro">
		  <h2>Password:</h2>
		  <input type="password" name="lastname" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;"><br>
		  <input type="submit" value="Enter">
		</form> 
	</div>
</body>
