<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";
?>
<div class="object subtitle">
	<h2>Information</h2>
</div>
<div class="editor">
	<label for="subtitle">Rubric Name: </label>
	<input id="subtitle" type="text" name="SUBTITLE" placeholder="Lab: Chemical Reactions"><br>
	<label for="maxpoints">Maximum points per criteria: </label>
	<input id="maxpoints" type="number" name="MAX_POINTS_PER_CRITERIA" placeholder="10"><br>
</div>
<a id="js_rubrics_create_submit" class="object create" href="#"><div class="arrow"></div><h3>Submit</h3></a>
<div class="object subtitle"><h2>About<br>"Maximum points per criteria"</h2></div>
<div class="object subtext">
	<p>Maximum points per criteria represents the maximum amount of points that a student can acheive per row on a rubric.
	<p>If you plan to score a student 9/10 in a criteria, the maximum points per criteria should be 10.</p>
</div>