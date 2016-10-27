<?php
#Libraries.
$needsFunction = true;
include "../../../../restricted/functions.php";
include "../../../../restricted/headrubric.php";

$RUBRIC_CRITERIA_NUM = isset($_POST["RUBRIC_CRITERIA_NUM"]) ? $_POST["RUBRIC_CRITERIA_NUM"] : "";
$RUBRIC_QUALITY_NUM = isset($_POST["RUBRIC_QUALITY_NUM"]) ? $_POST["RUBRIC_QUALITY_NUM"] : "";
$CONTENTS = isset($_POST["CONTENTS"]) ? $_POST["CONTENTS"] : "";

#First, check if the cell exists.
$cell = sql_getRubricCell($RUBRIC_CRITERIA_NUM, $RUBRIC_QUALITY_NUM);
if($cell === null) {
	db_showError("Whoops!","I could not find the cell you are editing inside my database","Try refreshing the page to fix the problem.",400);
}

#Then, check to see if the parent of the cell belongs to the rubric that we are editing.
$rubric = sql_getRubricNumberFromRubricQuality($cell["RUBRIC_QUALITY_NUM"]);
if($rubric["RUBRIC_NUM"] !== $NUM) {
	db_showError("Whoops!","You cannot edit cells that do not belong to your account.","Try refreshing the page to fix the problem.",400);
}

#Update the cell
sql_setRubricCellContents($RUBRIC_CRITERIA_NUM, $RUBRIC_QUALITY_NUM, $CONTENTS);
db_showError("Cell Updated!","Hey, you found an easter egg!","While you're looking at the debug tools, try not to die from looking at my code!",200);