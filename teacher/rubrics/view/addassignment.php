<?php
#Libraries.
$needsFunction = true;
include "../../../restricted/headrubric.php";
include "../../../restricted/functions.php"; ?>

<div class="object subtitle">
	<h2>Choose the assignment you want to add <?php echo  htmlentities($rubric["SUBTITLE"]); ?> to:</h2>
</div>

<?php
$assignments = sql_getAllAssignments($_SESSION["NUM"]);
fun_listAssignments("js_rubrics_addassignment_select", $assignments, "selectable", "", $RUBRIC_NUM);