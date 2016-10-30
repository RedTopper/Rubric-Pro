<?php
#Libraries.
$needsFunction = true;
include "../../restricted/headrubric.php";
include "../../restricted/functions.php";
?>
<div class="object subtitle">
	<h2><?php echo htmlentities($rubric["SUBTITLE"])?>: </h2>
</div>
<a id="js_rubrics_view_editrubric" class="object create white" href="#" data-rubricnum="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Edit cells</h3></a>
<a id="js_rubrics_view_addquality" class="object create white" href="#" data-rubricnum="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Edit qualities</h3></a>
<a id="js_rubrics_view_addcriteria" class="object create white" href="#" data-rubricnum="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Edit criteria</h3></a>
<a id="js_rubrics_view_destroyquality" class="object warn white" href="#" data-rubricnum="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Destroy qualities</h3></a>
<a id="js_rubrics_view_destroycriteria" class="object warn create" href="#" data-rubricnum="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Destroy criteria</h3></a>
<a id="js_rubrics_view_destroyrubric" class="object destroy" href="#" data-rubricnum="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Destroy <?php echo htmlentities($rubric["SUBTITLE"])?></h3></a>

<div class="object subtitle">
	<h2>Assignments:</h2>
</div>
<a id="js_rubrics_view_addassignment" class="object create white" href="#" data-rubricnum="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Add to assignment</h3></a>

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
	fun_listAssignments("js_rubrics_view_removeassignment", $assignments, "warn white", "Remove from ", $RUBRIC_NUM); ?>
	<div class="object subtext">
		<p>You can add a rubric to as many assignments as you wish.
	</div><?php
}

$componentTrees = sql_getAllCompiledSymbolTreesFromRubric($rubric["NUM"]); ?>

<div class="object spacer"></div>
<div class="object subtitle">
	<h2>Attached Components:</h2>
</div><?php

if($componentTrees === null) { ?>
<div class="object subtext">
	<p>Looks like you don't have any attached components.<br>You'll need to edit your criteria and bind some!</p>
</div><?php
} else { ?>
	<div class="object subtext"><?php
	foreach($componentTrees as $tree) {
		echo "<div class='rubriccriteria'>" . htmlentities($tree["COMPILED_SYMBOL_TREE"]) . " (" . htmlentities($tree["NAME"]) . ")</div>";
	} ?>
	</div><?php
}