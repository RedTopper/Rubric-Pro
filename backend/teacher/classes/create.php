<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";

#There isn't much here other than a web form.
?>
<div class="editor">
	<label for="classname">Class Name: </label>
	<input id="classname" type="text" name="NAME" placeholder="AP Computer Science"><br>
	<label for="year">Year: </label>
	<input id="year" type="number" name="YEAR" placeholder="2016"><br>
	<label for="term">Term: </label>
	<input id="term" type="number" name="TERM" placeholder="1"><br>
	<label for="period">Period: </label>
	<input id="period" type="text" name="PERIOD" placeholder="Period 8-9"><br>
	<label for="descriptor">Description: </label>
	<input id="descriptor" type="text" name="DESCRIPTOR" placeholder="Extra information"><br>
</div>
<a id="js_classes_create_submit" class="object create" href="#"><div class="arrow"></div><h3>Submit</h3></a>
<div class="object subtitle"><h2>Note</h2></div>
<div class="object subtext">
	<p>The description field can be left blank.
</div>