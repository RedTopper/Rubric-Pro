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

#Deturmine if the request is by AJAX or not
$usingAJAX = isset($_POST["AJAX"]);

#Shows an error code.
function showError($title = "Error", $header = "An unknown error occured.", $subheader = "Sorry about that :(", $status = 500) {
	http_response_code($status);
	if(!$usingAJAX) {  #If we are not using AJAX, send the doctype. ?>
<!DOCTYPE html>
<?php } ?>
<head>
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" href="/css/login.css"> 
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet"> 
	<meta charset="UTF-8">
	<meta name="author" content="Aaron Walter (2016)">
	<meta name="description" content="<?php echo $title; ?>">
</head>
<body>
	<div id="login">
		<img id="logo" src="/images/logo.png" alt="Rubric Pro">
		<h1><?php echo $header; ?></h1>
		<h2><?php echo $subheader; ?></h2>
		<a href="/">Back to login page</a>
	</div>
</body>
<?php
die();	
}

#Begin the session
session_start();

#Check the session
$sessionSet = isset($_SESSION['VALID']) && isset($_SESSION['TIMESTAMP']) && isset($_SESSION["USERNAME"]);

#Stop the page load if needsAuthentication is UNSET
if(!isset($needsAuthentication)) {
	showError("Server Error", "It was unable to be deturmined if this page requires authentication.", "Sorry about that :(", 500);
}

#Stop the page load if we cannot connect to the database
try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); #login
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); #Enable errors
} catch(PDOException $e) {
	showError("Database Error", "An error occured when connecting to the database.", "Sorry about that :(", 500);
}

#If we need authentication to view this page....
if($needsAuthentication) {
	
	#Stop the page load if something is not set or the session is not valid.
	if(!$sessionSet || !$_SESSION["VALID"]) {
		logout(); #If something is broke, make sure they really are logged out!
		showError("Forbidden", "You need to be logged in to do that!", "Sorry :(", 403);
	}
	
	#Stop the page load if the user timed out.
	if(strtotime(date("Y-m-d H:i:s")) - strtotime($_SESSION['TIMESTAMP']) > 60*60) {
		logout();
		showError("Forbidden", "Your session timed out.", "Sorry :/", 403);
	}
}
?>