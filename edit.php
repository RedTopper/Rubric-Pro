<?php
$needsAuthentication = true;
$needsAJAX = false;
$needsTeacher = true;
include "backend/db.php";
?>
<!DOCTYPE html>
<head>
	<title>Rubric Pro</title>
	<link rel="stylesheet" href="css/style.php"> 
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans|Ubuntu+Mono|Amatic+SC" rel="stylesheet"> 
	<meta charset="UTF-8">
	<meta name="author" content="Aaron Walter (2016)">
	<meta name="description" content="Edit your class rubrics and track progress with Rubric Pro!">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="/javascript/access.js"></script>
</head>
<body>
	<div id="contentscroller">
		<div id="content">
			<div id="sidebar">
				<p id="name">Crafted with &lt;3 by<br> Aaron Walter</p>
				<div id="logowrapper">
					<img id="logo" src="images/logo.png" alt="Rubric Pro">
				</div>
				<div id="navigation">
					<a href="#" id="js_dashboard"><span>Dashboard</span></a>
					<a href="#" id="js_classes"><span>Classes</span></a>
					<a href="#" id="js_accounts"><span>Accounts</span></a>
					<a href="#" id="js_rubrics"><span>Rubrics</span></a>
					<a href="#" id="js_catigories"><span>Catigories</span></a>
					<a href="#" id="js_settings"><span>Settings</span></a>
					<a href="/backend/logout.php"><span>Log out of <?php echo htmlspecialchars($_SESSION["USERNAME"]); ?></span></a>
				</div>
			</div>
		</div>
	 </div>
	<div id="logbar">
		<div id="console">
		</div>
	</div>
	<a href="#" id="js_consolebottom">View new message(s)</a>
</body>
