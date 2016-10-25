<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsSQL = true;
include "../../restricted/db.php";
include "../../restricted/sql.php";

$STUDENT = isset($_POST["STUDENT"]) ? $_POST["STUDENT"] : "";

if($STUDENT == "") {
	showError("Whoops!", "You need to select a student.", "Try refreshing the page to fix the problem.", 400);
}

#Check to see if there is really a student!
if(!sql_isTeacherAndStudentLinked($_SESSION["NUM"], $STUDENT)) {
	showError("Whoops!", "Something went wrong when requesting that student.", "Check to see if the student still exists and is linked to your account.", 400);
}
$info = sql_getStudentInformation($STUDENT);

#After this point, a file can alter student info. 
#This file is needed to check if a teacher can alter student information.