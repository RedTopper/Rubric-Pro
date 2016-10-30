<?php
#Libraries.
include "../../../../restricted/headassignment.php";

$ASSIGNMENT_NUM = isset($_POST["ASSIGNMENT_NUM"]) ? $_POST["ASSIGNMENT_NUM"] : "";
$DUE_DATE = isset($_POST["DUE_DATE"]) ? $_POST["DUE_DATE"] : "";

if($ASSIGNMENT_NUM == "") {
	db_showError("Whoops!", "You didn't select an assignment!", "Try selecting an assignment first.", 400);
}

$assignment = sql_doesTeacherOwnAssignment($_SESSION["NUM"], $ASSIGNMENT_NUM);

#Check if the teacher owns the assignment
if($assignment == null) {
	db_showError("Whoops!", "You can't add a rubric to an assignment that doesn't belong to you!", "Try selecting another assignment.", 400);
}

#If there is a duplicate, deny it.
if(sql_doesRubricAlreadyExistInAssignment($NUM, $ASSIGNMENT_NUM)) {
	db_showError("Whoops!", "That rubric already belongs in that assignment!", "Try selecting another rubric or assignment.", 400);
}

#Use access.js to clear all things after tier 1 (so the user doesn't loose their search)
header("JS-Redirect: removeto-3");

#Bind!
sql_bindRubricToAssignment($NUM, $ASSIGNMENT_NUM);

#Show that it's been bound
db_showError("Ok!", htmlentities($rubric["SUBTITLE"]) . " has been bound to " . htmlentities($assignment["TITLE"]) . ".", "", 201);