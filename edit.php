<?php
$needsAuthentication = true;
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
					<a href="#"><span>Dashboard</span></a>
					<a href="#"><span>Classes</span></a>
					<a href="#"><span>Students</span></a>
					<a href="#"><span>Rubrics</span></a>
					<a href="#"><span>Catigories</span></a>
					<a href="#"><span>Settings</span></a>
					<a href="#"><span>Logout of "admin"</span></a>
				</div>
			</div><!-- These comments are used to prevent the gap in inline-block elements.
	 --></div>
	 </div>
	<div id="logbar">
		<div id="console">
			[18:02:34] [Server thread/INFO]: Preparing spawn area: 0%<br>
			[18:02:35] [Server thread/INFO]: Preparing spawn area: 39%<br>
			[18:02:36] [Server thread/INFO]: Preparing spawn area: 85%<br>
			[18:02:36] [Server thread/INFO]: Done (3.943s)! For help, type "help" or "?"<br>
		</div>
	</div>
</body>
