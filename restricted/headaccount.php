<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsSQL = true;
include "db.php";
include "sql.php";

$STUDENT_NUM = isset($_POST["STUDENT_NUM"]) ? $_POST["STUDENT_NUM"] : "";

if($STUDENT_NUM == "") {
	db_showError("Whoops!", "You need to select a student.", "Try refreshing the page to fix the problem.", 400);
}

#Check to see if there is really a student!
if(!sql_isTeacherAndStudentLinked($_SESSION["NUM"], $STUDENT_NUM)) {
	db_showError("Whoops!", "Something went wrong when requesting that student.", "Check to see if the student still exists and is linked to your account.", 500);
}
$student = sql_getStudentInformation($STUDENT_NUM);

#After this point, a file can alter student info. 
#This file is needed to check if a teacher can alter student information.