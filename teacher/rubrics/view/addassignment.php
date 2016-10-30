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
if($assignments === null) { ?>
	<div class="object subtext">
		<p>Looks like you don't have any assignments yet.<br>Try creating one in the sidebar!
	</div><?php
} else {
	fun_listAssignments("js_rubrics_view_addassignment_select", $assignments, "selectable", "", $RUBRIC_NUM);
}
