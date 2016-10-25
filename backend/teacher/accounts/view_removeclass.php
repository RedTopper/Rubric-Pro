<?php
$CLASS = isset($_POST["CLASS"]) ? $_POST["CLASS"] : "";

include "../../restricted/view_verify.php";

###################################

$classname = "Unknown";

if($CLASS == "") {
	showError("Whoops!", "You didn't select a class!", "Try selecting a class first.", 400);
}

#Check if the teacher owns the class!
if(!doesTeacherOwnClass($_SESSION["NUM"], $CLASS, $classname)) {
	showError("Whoops!", "You can't remove a student from a class that doesn't belong to you!", "Try selecting another class.", 400);
}

#Use access.js to clear all things after tier 1 (so the user doesn't loose their search)
header("JS-Redirect: removeto1");

#Unbind!
unbindStudentFromClass($STUDENT, $CLASS);

#Show that it's been unbound
showError("Ok!", "The acccount has been unbound from " . htmlentities($classname) . ".", "", 201);