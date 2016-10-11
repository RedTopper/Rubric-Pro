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
	<link rel='stylesheet' href='javascript/nprogress.css'/>
	<meta charset="UTF-8">
	<meta name="author" content="Aaron Walter (2016)">
	<meta name="description" content="Edit your class rubrics and track progress with Rubric Pro!">
	<meta name="viewport" content="width=device-width, initial-scale=0.9">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="/javascript/access.js"></script>
	<script src='/javascript/nprogress.js'></script>
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
					<a href="#" id="js_components"><span>Components</span></a>
					<a href="#" id="js_rubrics"><span>Rubrics</span></a>
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
	<script>
	log("WELCOME/user", "Welcome to Rubric Pro! Actions you perform will appear down here.");
	</script>
</body>
