<?php
#Libraries.
$needsFunction = true;
include "../../../restricted/functions.php";
include "../../../restricted/headrubric.php";

#First, display the user input box so the user can actually create a new quality. ?>
<div class="object subtitle">
	<a href="#" data-document="QUALITY" class="js_help"><img class="help" src="images/help.svg" alt="Help" title="Help"></a>
	<h2>Create a new quality</h2>
</div>
<div class="editor">
	<label for="qualityname">Quality name: </label>
	<input id="qualityname" type="text" name="QUALITY_TITLE" placeholder="Proficient"><br>
	<label for="qualitypoints">Points out of <?php echo $rubric["MAX_POINTS_PER_CRITERIA"] ?>: </label>
	<input id="qualitypoints" type="number" name="POINTS" placeholder="<?php echo $rubric["MAX_POINTS_PER_CRITERIA"] ?>"><br>
</div>
<a id="js_rubrics_edit_addquality_submit" class="object create white" href="#" data-num="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Submit</h3></a><?php

#Then, output the qualities that are already in the table. ?>
<div class="object subtitle">
	<h2>Your Qualities</h2>
</div><?php

#Get qualities from database that match the rubric.
$qualities = sql_getAllQualitiesInRubric($rubric["NUM"], $differed);

#Output them if they exist, otherwise show that nothing exists. 
if($qualities === null) {
	?><div class="object subtext"><p>There's nothing here.</p></div><?php
} else {
	fun_listQuality("test", $qualities, $rubric["MAX_POINTS_PER_CRITERIA"]);
}