<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "../../restricted/db.php";
?>
<div class="object subtitle">
	<h2>Information</h2>
</div>
<div class="editor">
	<label for="title">Name: </label>
	<input id="title" type="text" name="TITLE" placeholder="Picture Lab"><br>
	<label for="description">Description: </label>
	<textarea id="description" name="DESCRIPTION" placeholder="Write some text here that will help the students understand the project." rows="8"></textarea><br>
</div>
<a id="js_assignment_create_submit" class="object create" href="#">
	<div class="arrow"></div>
	<h3>Submit</h3>
</a>