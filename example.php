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
		 --><div class="bar">
				<div class="title"><h1>Dashboard</h1></div>
				<div class="object subtitle"><h2>Subtag</h2></div>
				<div class="object subtext">
					<p>This is some really long object that might extend the size of the thing. Like, really, it will overflow because of how much text is in this single line.
					<p>Some other text may appear below the other one.
					<p>This is short
					<p>This is also a long line of text that extends beyond the boundries.
				</div>
				<a class="object selectable" href="#"><div class="arrow"></div><h1>Some Object</h1></a>
				<a class="object selectable" href="#"><div class="arrow"></div><h1>Some Object</h1></a>
				<a class="object selectable" href="#"><div class="arrow"></div><h1>Some Object</h1></a>
				<a class="object create" href="#"><div class="arrow"></div><h1>Create</h1></a>
			</div><!--
			--><div class="bar">
				<div class="title"><h1>Edit</h1></div>
				<form class="editor" method="post">
					<label for="tester">Username: </label>
					<input id="tester" type="text" name="test" placeholder="test"><br>
					<label for="tester2">Student ID: </label>
					<input id="tester2" type="text" name="test" placeholder="test"><br>
					<label for="tester3">Account Number: </label>
					<input id="tester3" type="text" name="test" placeholder="test"><br>
					<label for="tester4">Enabled: </label>
					<input id="tester4" type="checkbox" name="vehicle" value="Car"><br>
					
					<label for="tester5">Choice 1</label>
					<input id="tester5" type="radio" name="wow" value="other"><br>
					<label for="tester6">Choice 2</label>
					<input id="tester6" type="radio" name="wow" value="2"><br>
					<label for="tester7">Choice 3</label>
					<input id="tester7" type="radio" name="wow" value="3"><br>
					<label for="tester8">Choice 4</label>
					<input id="tester8" type="radio" name="wow" value="4"><br>
					<label for="tester9">Select one: </label>
					<select id="tester9">
						<option value="volvo">Volvo</option>
						<option value="saab">Saab</option>
						<option value="mercedes">Mercedes</option>
						<option value="audi">Audi</option>
					</select> 
				</form>
				<a class="object create" href="#"><div class="arrow"></div><h1>Create</h1></a>
				<a class="object destroy" href="#"><div class="arrow"></div><h1>Destroy</h1></a>
			</div><!--
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
