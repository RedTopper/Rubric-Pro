<?php
#Libraries.
include "../../../restricted/headassignment.php";

$CLASS_NUM = isset($_POST["CLASS_NUM"]) ? $_POST["CLASS_NUM"] : "";

if($CLASS_NUM == "") {
	db_showError("Whoops!", "You didn't select a class!", "Try selecting a class first.", 400);
}

$class = sql_doesTeacherOwnClass($_SESSION["NUM"], $CLASS_NUM);

if($class == null) {
	db_showError("Whoops!", "You can't remove an assignment from a class that doesn't belong to you!", "Try selecting another class or assignment.", 400);
}

header("JS-Redirect: removeto-2");

#Unbind!
sql_unbindAssignmentFromClass($NUM, $ASSIGNMENT_NUM);

#Show that it's been unbound
db_showError("Ok!", htmlentities($assignment["TITLE"]) . " has been unbound from " . htmlentities($class["NAME"]) . ".", "", 201);