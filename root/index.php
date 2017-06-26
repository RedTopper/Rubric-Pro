<!DOCTYPE html>
<head>
	<title>Rubric Pro: Login</title>
	<link rel="stylesheet" href="css/login.css"> 
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet"> 
	<?php include "./icon/icon.php" ?>
	<meta charset="UTF-8">
	<meta name="author" content="Aaron Walter (2016)">
	<meta name="description" content="Log in to Rubric Pro.">
	<meta name="viewport" content="width=device-width, initial-scale=0.9">
</head>
<body>
	<div id="login">
		<img id="logo" src="img/logo.svg" alt="Rubric Pro">
		<form method="post">
		  <h2>Username:</h2>
		  <input type="text" name="USERNAME" placeholder="rubricpro">
		  <h2>Password:</h2>
		  <input type="password" name="PASSWORD" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
		  <button type="submit">Enter</button>
		</form>
	</div>
    <div id="success">
		Something happened!
    </div>
</body>
