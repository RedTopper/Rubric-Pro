<?php
#Libraries.
$needsFunction = true;
include "../../restricted/headassignment.php";
include "../../restricted/functions.php";
?>
<div class="object subtitle">
	<h2><?php echo htmlentities($assignment["TITLE"]); ?></h2>
</div>
	<div class="object subtext">
	<p><?php echo ($assignment["DESCRIPTION"] !== "" ? "Description: <p>" . htmlentities($assignment["DESCRIPTION"]) : "No description.");  ?>
</div>

<div class="object subtitle">
	<h2>Classes:</h2>
</div>
<a id="js_assignment_view_bind" class="object create white" href="#" data-assignmentnum="<?php echo $assignment["NUM"] ?>">
	<div class="arrow"></div>
	<h3>Bind this assignment to a class</h3>
</a>

<?php
$classes = sql_getAllAssignmentClasses($_SESSION['NUM'], $assignment["NUM"]);

#If there are no classes
if($classes === null) {	
	#Show a tip to add an assignment to a class. ?>
	
	<div class="object subtext">
		<p>You can use the button above to bind an assignment to a class.
	</div><?php
} else {
	
	#Print every class.
	 fun_listClasses("js_assignment_view_removeclasses", $classes, "warn", "Remove this assignment from ", $assignment["NUM"]); ?>
	<div class="object subtext">
		<p>You can add an assignment to as many classes as you wish.
	</div><?php
}

$rubrics = sql_getAllRubricsInAssignment($_SESSION['NUM'], $assignment["NUM"]); ?>

<div class="object spacer"></div>
<div class="object subtitle">
	<h2>Attached Rubrics:</h2>
</div><?php

if($rubrics === null) { ?>
<div class="object subtext">
	<p>Looks like you don't have any rubrics bound to this assignment yet.<br>Try binding one with the button above!</p>
</div><?php
} else {
	fun_listRubrics("nothing", $rubrics, "");
}