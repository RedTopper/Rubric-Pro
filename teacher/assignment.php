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
	<h2>Your Assignments</h2>
</div>
<a id="js_assignment_create" class="object create" href="#">
	<div class="arrow">
	</div><h3>Create new assignment</h3>
</a>
<?php

#Get all of the assignments that the teacher has.
$assignments = sql_getAllAssignments($_SESSION["NUM"]);

if($assignments === null) { ?>
<div class="object subtext">
	<p>Looks like you don't have any assignments yet.
	<p>Try creating one with the button above!
</div><?php
} else {
	fun_listAssignments("js_assignments_select", $assignments);
}