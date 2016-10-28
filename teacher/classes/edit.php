<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsFunction = true;
$needsSQL = true;
include "../../restricted/db.php";
include "../../restricted/functions.php";
include "../../restricted/sql.php";

$CLASS = isset($_POST["CLASS"]) ? $_POST["CLASS"] : "";

$class = sql_doesTeacherOwnClass($_SESSION["NUM"], $CLASS);

#Check to see if there is really a student!
if($class == null) {
	db_showError("Whoops!", "Something went wrong when requesting that class.", "Check to see if you are the owner of that class, or if your client sent the wrong class ID.", 400);
}

#Show general information ?>
<div class="object subtitle">
	<h2><?php echo htmlentities($class["NAME"]); ?></h2>
</div>
	<div class="object subtext">
	<p><?php echo "Year: " . $class["YEAR"]; ?>.
	<p><?php echo "Term: " . $class["TERM"]; ?>.
	<p><?php echo htmlentities($class["PERIOD"]); ?>.
	<p><?php echo ($class["DESCRIPTOR"] !== "" ? "Description: " . htmlentities($class["DESCRIPTOR"]) . "." : "");  ?>
</div>

<div class="object subtitle">
	<h2>Current assignments:</h2>
</div><?php

$currentAssignments = null; #sql_getAllCurrentAssignments($CLASS);
if($currentAssignments === null) {
	#There are no current assignments ?>
	</div>
		<div class="object subtext">
		<p>No assignments are currently active in this class.
		<p>Try adding some through the "Assignmments" tab.
	</div><?php
} else {
	#fun_listAssignments($currentAssignments); ?>
	</div>
		<div class="object subtext">
		<p>You can assign assignments through the "Assignmments" tab.
	</div><?php
} ?>

<div class="object subtitle">
	<h2>Past assignments:</h2>
</div><?php

$pastAssignments = null; #sql_getAllPastAssignments($CLASS);
if($pastAssignments === null) {
	#No past assignments either. ?>
	</div>
		<div class="object subtext">
		<p>No assignments have ever been active in this class.
		<p>Try adding some through the "Assignmments" tab.
	</div><?php
} else {
	#fun_listAssignments($pastAssignments);	?>
	</div>
		<div class="object subtext">
		<p>You can assign assignments through the "Assignmments" tab.
	</div><?php
} 

#Students that belong to this class ?>
<div class="object subtitle">
	<h2>Students:</h2>
</div>
<?php

#Gets a list of students in a class that belongs to the logged in teacher.
$students = sql_getListOfStudentsViaClass($class["NUM"]);
if($students == null) { 
	#There are no students.?>
	</div>
		<div class="object subtext">
		<p>No students belong to this class.
		<p>Try adding some through the accounts tab.
	</div><?php
} else {
	#Display students. Last false means not selectable (as in you cannot click on it)
	fun_listStudents("idkbro", $students, false); ?>
	</div>
		<div class="object subtext">
		<p>You can add and remove more students throught the "Accounts" tab.
	</div><?php
}