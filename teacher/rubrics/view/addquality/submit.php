<?php
#Libraries.
include "../../../../restricted/headrubric.php";

$POINTS = isset($_POST["POINTS"]) ? $_POST["POINTS"] : "";
$QUALITY_TITLE = isset($_POST["QUALITY_TITLE"]) ? $_POST["QUALITY_TITLE"] : "";

#Quick verification of input.
if(strlen($POINTS) < 1 || !is_numeric($POINTS)) {
	db_showError("Error creating quality!", "The points must be numerical.", "Check what you typed, then try again.", 400);
}

sql_createQuality($rubric["NUM"], $POINTS, $QUALITY_TITLE);

header("JS-Redirect: removeto-2");

db_showError("Ok!", "The quality has been added to your rubric.", "", 201);