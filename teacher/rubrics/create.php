<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "../../restricted/db.php";
?>
<div class="object subtitle">
	<a href="#" data-document="MAXPOINTSPERCRITERIA" class="js_help"><img class="help" src="images/help.svg" alt="Help" title="Help"></a>
	<h2>Information</h2>
</div>
<div class="editor">
	<label for="subtitle">Rubric Name: </label>
	<input id="subtitle" type="text" name="SUBTITLE" placeholder="Lab: Chemical Reactions"><br>
	<label for="maxpoints">Maximum points per criteria: </label>
	<input id="maxpoints" type="number" name="MAX_POINTS_PER_CRITERIA" placeholder="10"><br>
</div>
<a id="js_rubrics_create_submit" class="object create" href="#"><div class="arrow"></div><h3>Submit</h3></a>