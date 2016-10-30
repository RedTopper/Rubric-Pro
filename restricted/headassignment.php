<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsSQL = true;
include "db.php";
include "sql.php";

$ASSIGNMENT_NUM = isset($_POST["ASSIGNMENT_NUM"]) ? $_POST["ASSIGNMENT_NUM"] : "";

if($ASSIGNMENT_NUM == "") {
	db_showError("Whoops!", "You need to select an assignment.", "Try refreshing the page to fix the problem.", 400);
}

$assignment = sql_doesTeacherOwnAssignment($_SESSION["NUM"], $ASSIGNMENT_NUM);

if($assignment === null) {
	db_showError("Whoops!", "That assignment doesn't belong to you.", "Try selecting another assignment or refresh the page.", 400);
}