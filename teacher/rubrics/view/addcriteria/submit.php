<?php
#Libraries.
include "../../../../restricted/headrubric.php";

$CRITERIA_TITLE = isset($_POST["CRITERIA_TITLE"]) ? $_POST["CRITERIA_TITLE"] : "";

#Verification of name.
if(strlen($CRITERIA_TITLE) < 2) {
	db_showError("Error creating criteria!", "The name must be longer than 1 character.", "Check what you typed, then try again.", 400);
}

sql_createCriteria($rubric["NUM"], $CRITERIA_TITLE);

header("JS-Redirect: removeto-2");

db_showError("Ok!", "The criteria has been added to your rubric.", "", 201);