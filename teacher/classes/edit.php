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
</div><?php

#Project management section ?>
<div class="object subtitle">
	<h2>Assignment management:</h2>
</div>
<a id="js_classes_edit_viewprojects" class="object create" href="#"><div class="arrow"></div>
	<h3>Bind</h3>
</a>
<a id="js_classes_edit_createprojects" class="object warn white" href="#"><div class="arrow"></div>
	<h3>Unbind</h3>
</a>
<?php

#Students that belong to this class ?>
<div class="object subtitle">
	<h2>Current members:</h2>
</div>
<?php

#Gets a list of students in a class that belongs to the logged in teacher.
$students = sql_getListOfStudentsViaClass($class["NUM"]);
if($students == null) { ?>
	</div>
		<div class="object subtext">
		<p>No students belong to this class. Try adding some through the accounts tab.
	</div>
	<?php die();
}

#Display students. Last false means not selectable (as in you cannot click on it)
fun_listStudents("idkbro", $students, false); ?>

</div>
	<div class="object subtext">
	<p>You can add and remove more students throught the "Accounts" tab.
</div>