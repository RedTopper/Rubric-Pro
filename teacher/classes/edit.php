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

$CLASS_NUM = isset($_POST["CLASS_NUM"]) ? $_POST["CLASS_NUM"] : "";

$class = sql_doesTeacherOwnClass($_SESSION["NUM"], $CLASS_NUM);

#Check to see if there is really a student!
if($class == null) {
	db_showError("Whoops!", "Something went wrong when requesting that class.", "Check to see if you are the owner of that class, or if your client sent the wrong class ID.", 400);
}

#Show general information ?>
<div class="object subtitle">
	<h2><?php echo htmlentities($class["NAME"]); ?></h2>
</div>
	<div class="object subtext">
	<p><?php echo "Year: " . $class["YEAR"]; ?>
	<p><?php echo "Term: " . $class["TERM"]; ?>
	<p><?php echo htmlentities($class["PERIOD"]); ?>
	<p><?php echo ($class["DESCRIPTOR"] !== "" ? "Description: " . htmlentities($class["DESCRIPTOR"]) . "." : "");  ?>
</div>

<div class="object spacer"></div>

<div class="object subtitle" id="js_tutorial_activeassn">
	<h2>Attached Active Assignments:</h2>
</div><?php

$currentAssignments = sql_getAllCurrentAssignments($CLASS_NUM);
if($currentAssignments === null) {
	#There are no current assignments ?>
	</div>
		<div class="object subtext">
		<p>No assignments are currently active in this class.
		<p>Try adding some through the <a href="#" id="js_assignments" class="floatinglink"><span>Assignments</span></a> tab.
	</div><?php
} else {
	fun_listAssignments("js_assignments_view_link", $currentAssignments, "selectable", "", null, true); ?>
	</div>
		<div class="object subtext">
		<p>You can add and remove more assignments through <a href="#" id="js_assignments" class="floatinglink"><span>Assignments</span></a>.
	</div><?php
} ?>

<div class="object subtitle" id="js_tutorial_pastassn">
	<h2>Attached Past Assignments:</h2>
</div><?php

$pastAssignments = sql_getAllPastAssignments($CLASS_NUM);
if($pastAssignments === null) {
	#No past assignments either. ?>
	<div class="object subtext">
		<p>No assignments have ever been active in this class.
		<p>Try adding some through the <a href="#" id="js_assignments" class="floatinglink"><span>Assignments</span></a> tab.
	</div><?php
} else {
	fun_listAssignments("js_assignments_view_link", $pastAssignments, "selectable", "", null, true);	?>
	<div class="object subtext">
		<p>You can add and remove more assignments through <a href="#" id="js_assignments" class="floatinglink"><span>Assignments</span></a>.
	</div><?php
} 

#Students that belong to this class ?>
<div class="object subtitle" id="js_tutorial_student">
	<h2>Attached Students:</h2>
</div>
<?php

#Gets a list of students in a class that belongs to the logged in teacher.
$students = sql_getAllStudentsInClass($class["NUM"]);
if($students == null) { 
	#There are no students.?>
	<div class="object subtext">
		<p>No students belong to this class.
		<p>Try adding some through the <a href="#" id="js_accounts" class="floatinglink"><span>Accounts</span></a> tab.
	</div><?php
} else {
	#Display students. Last false means not selectable (as in you cannot click on it)
	fun_listStudents("js_accounts_view_link", $students); ?>
	<div class="object subtext">
		<p>You can add and remove more students through <a href="#" id="js_accounts" class="floatinglink"><span>Accounts</span></a>.
	</div><?php
}