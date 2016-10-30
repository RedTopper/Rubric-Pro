<?php
#Libraries.
$needsFunction = true;
include "../../restricted/headrubric.php";
include "../../restricted/functions.php";
?>
<div class="object subtitle">
	<h2><?php echo htmlentities($rubric["SUBTITLE"])?>: </h2>
</div>
<a id="js_rubrics_view_editrubric" class="object create white" href="#" data-rubricnum="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Edit this rubric</h3></a>
<a id="js_rubrics_view_addquality" class="object create white" href="#" data-rubricnum="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Edit qualities</h3></a>
<a id="js_rubrics_view_addcriteria" class="object create white" href="#" data-rubricnum="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Edit criteria</h3></a>
<a id="js_rubrics_view_destroyquality" class="object warn white" href="#" data-rubricnum="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Choose and destroy qualities</h3></a>
<a id="js_rubrics_view_destroycriteria" class="object warn create" href="#" data-rubricnum="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Choose and destroy criteria</h3></a>
<a id="js_rubrics_view_destroyrubric" class="object destroy" href="#" data-rubricnum="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Destroy this rubric</h3></a>

<div class="object subtitle">
	<h2>Assignments:</h2>
</div>
<a id="js_rubrics_view_addassignment" class="object create white" href="#" data-rubricnum="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Bind this rubric to an assignment</h3></a>

<?php
$assignments = sql_getAllRubricAssignments($_SESSION['NUM'], $rubric["NUM"]);

#If there are no classes
if($assignments == null) {	
	#Show a tip to add a student to a class. ?>
	
	<div class="object subtext">
		<p>You can use the button above to bind a rubric to an assignment.
	</div><?php
} else {
	
	#Print every class.
	fun_listAssignments("js_rubrics_view_removeassignment", $assignments, "warn", "Remove this rubric from ", $RUBRIC_NUM); ?>
	<div class="object subtext">
		<p>You can add a rubric to as many assignments as you wish.
	</div><?php
}