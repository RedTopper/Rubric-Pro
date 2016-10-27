<?php
$needsAuthentication = true;
$needsAJAX = false;
$needsTeacher = true;
include "./restricted/db.php";

$version = shell_exec("git describe --long --tags --dirty --always");
$version = ($version != null ? $version : (file_exists("version") ? fgets(fopen("version", 'r')) : "Unknown Version"));

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
	<meta name="viewport" content="width=device-width, initial-scale=0.6">
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
					<div id="version"><?php echo $version; ?></div>
				</div>
				<div id="navigation">
					<a href="#" id="js_dashboard"><img class="navicon" src="images/sidebar/dashboard.svg"><span>Dashboard</span></a>
					<a href="#" id="js_classes"><img class="navicon" src="images/sidebar/class.svg"><span>Classes</span></a>
					<a href="#" id="js_accounts"><img class="navicon" src="images/sidebar/accounts.svg"><span>Accounts</span></a>
					<a href="#" id="js_components"><img class="navicon" src="images/sidebar/component.svg"><span>Components</span></a>
					<a href="#" id="js_rubrics"><img class="navicon" src="images/sidebar/rubrics.svg"><span>Rubrics</span></a>
					<a href="#" id="js_assignments"><img class="navicon" src="images/sidebar/assignment.svg"><span>Assignments</span></a>
					<div class="smallspacer"></div>
					<a href="/logout.php"><img class="navicon" src="images/sidebar/logout.svg"><span>Log out</span></a>
				</div>
			</div>
		</div>
	 </div>
	<div id="logbar">
		<div id="console">
		</div>
	</div>
	<a href="#" class="consolebutton" id="js_consolebottom">View new message(s)</a>
	<a href="#" class="consolebutton" id="js_consoleshow" style="display: block">Show developer console</a>
</body>
