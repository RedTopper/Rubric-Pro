<?php
$needsAuthentication = true;
$needsAJAX = true;
include "db.php";

function outputForm() {	
?>
<div class="editor">
	<label for="first">First Name: </label>
	<input id="first" type="text" name="FIRST_NAME" placeholder="Jon"><br>
	<label for="last">Last Name: </label>
	<input id="last" type="text" name="LAST_NAME" placeholder="Snow"><br>
	<label for="username">Username: </label>
	<input id="username" type="text" name="USERNAME" placeholder="lordsnow"><br>
	<label for="studentid">Student ID: </label>
	<input id="studentid" type="text" name="STUDENT_ID" placeholder="1662289"><br>
	<label for="type">Type: </label>
	<select id="type">
		<option value="0">Student</option>
		<option value="1">Teacher</option>
	</select> 
	<label for="parent">Parent: </label>
	<select id="parent">
		<option value="root">root</option>
	</select> 
</div>
<a id="js_accounts_create" class="object create" href="#"><div class="arrow"></div><h1>Submit</h1></a>
<div class="object subtitle"><h2>Notice</h2></div>
<div class="object subtext">
	<p>Students will be able to create their passwords the first time they log in.
</div>
<?php
}

switch ($_SESSION["TYPE"]) {
	case 0:
		showError("Not Allowed", "Students may not create other student accounts.", "How did you even request this?", 403);
		break;
	case 1:
		break;
	case 2:
		outputForm();
		break;
}
?>