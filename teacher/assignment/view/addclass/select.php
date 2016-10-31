<?php
#Libraries.
include "../../../../restricted/headassignment.php";

$CLASS_NUM = isset($_POST["CLASS_NUM"]) ? $_POST["CLASS_NUM"] : "";
$DUE_DATE = isset($_POST["DUE_DATE"]) ? $_POST["DUE_DATE"] : "";

if($CLASS_NUM == "") {
	db_showError("Whoops!", "You didn't select a class!", "Try selecting a class first.", 400);
}

if($DUE_DATE == "") {
	db_showError("Whoops!", "You didn't select a date!", "You need to assign a due date to continue.", 400);
}

$dateParts = explode("/", $DUE_DATE);
if(!(count($dateParts) === 3 && checkdate ($dateParts[0] , $dateParts[1] , $dateParts[2]))) {
	db_showError("Whoops!", "Something seems wrong about that date!", "You need to pick a date or format it in MM/DD/YYYY.", 400);
}

$class = sql_doesTeacherOwnClass($_SESSION["NUM"], $CLASS_NUM);

#Check if the teacher owns the assignment
if($class == null) {
	db_showError("Whoops!", "You can't add an assignment to a class that doesn't belong to you!", "Try selecting another class or assignment.", 400);
}

#If there is a duplicate, deny it.
if(sql_doesAssignmentAlreadyExistInClass($ASSIGNMENT_NUM, $CLASS_NUM)) {
	db_showError("Whoops!", "That assignment already belongs in that class!", "Try selecting another assignment or class.", 400);
}

#Use access.js to clear all things after tier 1
header("JS-Redirect: removeto-3");

#Bind!
sql_bindAssignmentToClass($ASSIGNMENT_NUM, $CLASS_NUM, $dateParts[2],  $dateParts[0],  $dateParts[1]);

#Show that it's been bound
db_showError("Ok!", htmlentities($assignment["TITLE"]) . " has been bound to " . htmlentities($class["NAME"]) . " with the due date " . htmlentities($DUE_DATE) . ".", "", 201);