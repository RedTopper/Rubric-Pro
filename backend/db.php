<?php
include "passwd.php";

#Stop the page load if needsAuthentication is UNSET
if(!isset($needsAuthentication)) {
	http_response_code(500); #Send 500/Internal Server Error
?>
<!DOCTYPE html>
<head>
	<title>Error</title>
	<link rel="stylesheet" href="/css/login.css"> 
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet"> 
	<meta charset="UTF-8">
	<meta name="author" content="Aaron Walter (2016)">
	<meta name="description" content="Page Error">
</head>
<body>
	<div id="login">
		<img id="logo" src="/images/logo.png" alt="Rubric Pro">
		<h1>It was unable to be deturmined if this page requires authentication.</h1>
		<h2>Sorry about that :(</h2>
	</div>
</body>
<?php
die();
}

#Stop the page load if we cannot connect to the database
try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); #login
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); #Enable errors
} catch(PDOException $e) {
	http_response_code(500); #Send 500/Internal Server Error
?>
<!DOCTYPE html>
<head>
	<title>Error</title>
	<link rel="stylesheet" href="/css/login.css"> 
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet"> 
	<meta charset="UTF-8">
	<meta name="author" content="Aaron Walter (2016)">
	<meta name="description" content="Database Error">
</head>
<body>
	<div id="login">
		<img id="logo" src="/images/logo.png" alt="Rubric Pro">
		<h1>An error occured when connecting to the database.</h1>
		<h2>Sorry about that :(</h2>
	</div>
</body>
<?php
die();
}

#Begin the session
session_start();

#Stop the page load if we are not authenticated to view this page.
if($needsAuthentication && !isset($_SESSION["USERNAME"])) {
	http_response_code(403); #Send 403/Forbidden
?>
<!DOCTYPE html>
<head>
	<title>Error</title>
	<link rel="stylesheet" href="/css/login.css"> 
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet"> 
	<meta charset="UTF-8">
	<meta name="author" content="Aaron Walter (2016)">
	<meta name="description" content="Authentication Error">
</head>
<body>
	<div id="login">
		<img id="logo" src="/images/logo.png" alt="Rubric Pro">
		<h1>You need to be logged in to do that!</h1>
		<h2>Sorry about that :(</h2>
		<a href="/">Back to login page</a>
	</div>
</body>
<?php
die();
}
?>