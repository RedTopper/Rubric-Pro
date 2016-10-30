<?php
#Libraries.
include "../../../restricted/headrubric.php";

$ASSIGNMENT_NUM = isset($_POST["ASSIGNMENT_NUM"]) ? $_POST["ASSIGNMENT_NUM"] : "";

if($ASSIGNMENT_NUM == "") {
	db_showError("Whoops!", "You didn't select an assignment!", "Try selecting an assignment first.", 400);
}

$assignment = sql_doesTeacherOwnAssignment($_SESSION["NUM"], $ASSIGNMENT_NUM);

if($assignment == null) {
	db_showError("Whoops!", "You can't remove a rubric from an assignment that doesn't belong to you!", "Try selecting another assignment or rubric.", 400);
}

header("JS-Redirect: removeto-2");

#Unbind!
sql_unbindRubricFromAssignment($RUBRIC_NUM, $ASSIGNMENT_NUM);

#Show that it's been unbound
db_showError("Ok!", htmlentities($rubric["SUBTITLE"]) . " has been unbound from " . htmlentities($assignment["TITLE"]) . ".", "", 201);