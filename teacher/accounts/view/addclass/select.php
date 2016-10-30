<?php
#Libraries.
include "../../../../restricted/headaccount.php";

$CLASS_NUM = isset($_POST["CLASS_NUM"]) ? $_POST["CLASS_NUM"] : "";

if($CLASS_NUM == "") {
	db_showError("Whoops!", "You didn't select a class!", "Try selecting a class first.", 400);
}

$class = sql_doesTeacherOwnClass($_SESSION["NUM"], $CLASS_NUM);

#Check if the teacher owns the class
if($class == null) {
	db_showError("Whoops!", "You can't add a student to a class that doesn't belong to you!", "Try selecting another class.", 400);
}

#If there is a duplicate, deny it.
#We don't need 10,000 of the same entry
if(sql_doesStudentAlreadyExistInClass($STUDENT_NUM, $CLASS_NUM)) {
	db_showError("Whoops!", "That student already belongs to that class!", "Try selecting another class or student.", 400);
}

#Use access.js to clear all things after tier 1 (so the user doesn't loose their search)
header("JS-Redirect: removeto1");

#Bind!
sql_bindStudentToClass($STUDENT_NUM, $CLASS_NUM);

#Show that it's been bound
db_showError("Ok!", htmlentities($info["FIRST_NAME"]) . " " . htmlentities($info["LAST_NAME"]) . " has been bound to " . htmlentities($class["NAME"]) . ".", "", 201);