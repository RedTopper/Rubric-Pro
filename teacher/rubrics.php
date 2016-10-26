<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsFunction = true;
$needsSQL = true;
include "../restricted/db.php";
include "../restricted/functions.php";
include "../restricted/sql.php";
?>
<div class="object subtitle">
	<h2>Your Rubrics</h2>
</div>
<a id="js_rubrics_create" class="object create" href="#"><div class="arrow"></div><h3>Create new rubric</h3></a><?php

#Get all of the rubrics from the signed in teacher.
$rubrics = sql_getAllRubricsFromTeacher($_SESSION["NUM"]);

if($rubrics === null) { ?>
<div class="object subtitle"><h2>Hey!</h2></div>
<div class="object subtext">
	<p>Looks like you don't have any rubrics yet.<br>Try creating one with the button above!</p>
</div><?php
} else {
	fun_listRubrics("js_rubrics_select", $rubrics);
}