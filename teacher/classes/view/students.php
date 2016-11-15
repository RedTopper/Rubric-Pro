<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsFunction = true;
$needsSQL = true;
include "../../../restricted/db.php";
include "../../../restricted/functions.php";
include "../../../restricted/sql.php";

$CLASS_NUM = isset($_POST["CLASS_NUM"]) ? $_POST["CLASS_NUM"] : "";
$ASSIGNMENT_NUM = isset($_POST["ASSIGNMENT_NUM"]) ? $_POST["ASSIGNMENT_NUM"] : "";

$class = sql_doesTeacherOwnClass($_SESSION["NUM"], $CLASS_NUM);

#Check to see if there is really a class!
if($class == null) {
	db_showError("Whoops!", "Something went wrong when requesting that class.", "Check to see if you are the owner of that class, or if your client sent the wrong class ID.", 400);
}

#Students that belong to this class ?>
<div class="object subtitle">
	<h2>Grade who?</h2>
</div><?php

#Gets a list of students in a class that belongs to the logged in teacher.
$students = sql_getAllStudentsInClass($class["NUM"]);
if($students == null) { 
	#There are no students.?>
	<div class="object subtext">
		<p>No students belong to this class.
		<p>Try adding some through the <a href="#" id="js_accounts" class="floatinglink"><span>Accounts</span></a> tab.
	</div><?php
} else {
	fun_listStudents("js_classes_edit_students_grade", $students);
}