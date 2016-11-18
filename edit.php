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
	<!-- Styles -->
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans|Ubuntu+Mono|Amatic+SC" rel="stylesheet"> 
	<link rel="stylesheet" href="css/style.css"> 
	<link rel="stylesheet" href="css/ui.css">
	<link rel="stylesheet" href="css/tutorial.css">
	<link rel="stylesheet" href="css/credits.css">
	<link rel='stylesheet' href='javascript/nprogress.css'/>
	<!-- Favicons -->
	<?php include "./favicons/icon.php" ?>
	<!-- Metadata -->
	<meta charset="UTF-8">
	<meta name="author" content="Aaron Walter (2016)">
	<meta name="description" content="Edit your class rubrics and track progress with Rubric Pro!">
	<meta name="viewport" content="width=device-width, initial-scale=0.7,maximum-scale=0.7,user-scalable=no">
	<!-- Javascript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="/javascript/access.js"></script>
	<script src="/javascript/tutorial.js"></script>
	<script src='/javascript/nprogress.js'></script>
	<script src='/javascript/credits.js'></script>
</head>
<body>
	<div id="contentscroller">
		<div id="content">
			<div id="sidebar">
				<p id="name">Crafted with &lt;3 by<br> Aaron Walter</p>
				<div id="logowrapper">
					<img id="logo" src="images/sidebar/logo.svg" alt="Rubric Pro">
					<div id="version"><?php echo $version; ?></div>
				</div>
				<div id="navigation">
					<a href="#" id="js_tutorial"><img class="navicon" src="images/sidebar/tutorial.svg"><span>Tutorial</span></a>
					<a href="#" id="js_components"><img class="navicon" src="images/sidebar/component.svg"><span>Components</span></a>
					<a href="#" id="js_accounts"><img class="navicon" src="images/sidebar/accounts.svg"><span>Accounts</span></a>
					<a href="#" id="js_rubrics"><img class="navicon" src="images/sidebar/rubrics.svg"><span>Rubrics</span></a>
					<a href="#" id="js_assignments"><img class="navicon" src="images/sidebar/assignment.svg"><span>Assignments</span></a>
					<a href="#" id="js_classes"><img class="navicon" src="images/sidebar/class.svg"><span>Classes</span></a>
					<a href="#" id="js_dashboard"><img class="navicon" src="images/sidebar/dashboard.svg"><span>Dashboard</span></a>
					<div class="smallspacer"></div>
					<a href="mailto:red@rubric.me?subject=Rubric%20Pro%20Feedback&amp;body=Please%20describe%20the%20feedback%2C%20problem%2C%20or%20suggestion%20with%20detail%20here. Thanks%20for%20using%20Rubric%20Pro!" id="js_mail"><img class="navicon" src="images/sidebar/mail.svg"><span>Feedback</span></a>
					<a href="#" id="js_credits"><img class="navicon" src="images/sidebar/credits.svg"><span>Credits</span></a>
					<a href="/logout.php"><img class="navicon" src="images/sidebar/logout.svg"><span>Log out</span></a>
					<div class="smallspacer"></div>
					<div class="smallspacer"></div>
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
