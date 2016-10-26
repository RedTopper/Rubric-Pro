<?php
#Libraries.
include "../../../restricted/headaccount.php";

$CLASS = isset($_POST["CLASS"]) ? $_POST["CLASS"] : "";

if($CLASS == "") {
	db_showError("Whoops!", "You didn't select a class!", "Try selecting a class first.", 400);
}

$class = sql_doesTeacherOwnClass($_SESSION["NUM"], $CLASS);

#Check if the teacher owns the class!
if($class == null) {
	db_showError("Whoops!", "You can't remove a student from a class that doesn't belong to you!", "Try selecting another class.", 400);
}

#Use access.js to clear all things after tier 1 (so the user doesn't loose their search)
header("JS-Redirect: removeto1");

#Unbind!
sql_unbindStudentFromClass($STUDENT, $CLASS);

#Show that it's been unbound
db_showError("Ok!", htmlentities($info["FIRST_NAME"]) . " " . htmlentities($info["LAST_NAME"]) . " has been unbound from " . htmlentities($class["NAME"]) . ".", "", 201);