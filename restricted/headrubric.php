<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsSQL = true;
include "db.php";
include "sql.php";

$NUM = isset($_POST["NUM"]) ? $_POST["NUM"] : "";

if($NUM == "") {
	db_showError("Whoops!", "You need to select a rubric.", "Try refreshing the page to fix the problem.", 400);
}

$rubric = sql_doesTeacherOwnRubric($_SESSION["NUM"], $NUM);

#Check to see if there is really a student!
if($rubric === null) {
	db_showError("Whoops!", "That rubric number doesn't belong to you.", "Try selecting another rubric or refresh the page.", 400);
}

#After this point, a file can alter rubric data. 