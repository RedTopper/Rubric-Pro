<?php
#Libraries.
$needsFunction = true;
include "../../../restricted/functions.php";
include "../../../restricted/headrubric.php";

#output creation zone?>
<div class="object subtitle">
	<a href="#" data-document="CRITERIA" class="js_help"><img class="help" src="images/help.svg" alt="Help" title="Help"></a>
	<h2>Create a new criteria</h2>
</div>
<div class="editor">
	<label for="criterianame">Criteria name: </label>
	<input id="criterianame" type="text" name="CRITERIA_TITLE" placeholder="Spelling and Accuracy"><br>
</div>
<a id="js_rubrics_edit_addcriteria_submit" class="object create white" href="#" data-rubricnum="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Submit</h3></a><?php 

#Show contents of the rubric. ?>
<div class="object subtitle">
	<h2>Add Components to Criteria</h2>
</div><?php

$criteria = sql_getAllCriteriaInRubric($rubric["NUM"]);	
if($criteria === null) {
	?><div class="object subtext"><p>There's nothing here.</p></div><?php
} else {
	fun_listCriterion("js_rubrics_edit_addcriteria_addcomponent", $criteria, "selectable", $rubric["NUM"]);
}