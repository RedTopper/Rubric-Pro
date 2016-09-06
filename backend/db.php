<?php
include "passwd.php";

#Logs a user out of the page.
function logout() {
	if(isset($_SESSION['VALID'])) { 
		$_SESSION['VALID'] = false; #Makes sure the session is killed.
	}
	session_destroy();
	header("Location: /index.php");
}

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

$sessionValid = isset($_SESSION['VALID']) && isset($_SESSION['TIMESTAMP']) && isset($_SESSION["USERNAME"]);
#Not authenticated means:	this page REQUIRES authentication and (something is not set OR the session is not valid OR
#							the time logged in is greater than 60 minutes)

#Stop the page load if we are not authenticated to view this page.
if($needsAuthentication && (!$sessionValid || !$_SESSION["VALID"] || (strtotime(date("Y-m-d H:i:s")) - strtotime($_SESSION['TIMESTAMP']) > 10))) {
	logout(); #If something is broke, make sure they really are logged out!
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
		<h2>Sorry :(</h2>
		<a href="/">Back to login page</a>
	</div>
</body>
<?php
die();
}
?>