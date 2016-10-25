<?php
$STUDENT = isset($_POST["STUDENT"]) ? $_POST["STUDENT"] : "";

#Initialize db.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "../../restricted/db.php";

#Include SQL functions
$needsSQL = true;
include "../../restricted/sql.php";

###################################

#Check to see if there is really a student!
if(!isTeacherAndStudentLinked($_SESSION["NUM"], $STUDENT)) {
	showError("Whoops!", "Something went wrong when requesting that student.", "Check to see if the student still exists and is linked to your account.", 400);
}
$info = getStudentInformation($STUDENT);

?>
<div class="object subtitle">
	<h2>Really reset <?php echo  htmlentities($info["FIRST_NAME"]) . " " . htmlentities($info["LAST_NAME"]); ?>'s password?</h2>
</div>
<div class="object subtext">
	<p>They'll be able to set a new password the next time they log in.
</div>
<a id="js_accounts_student_reset_yes" class="object warn white" href="#" data-num="<?php echo $info["NUM"]; ?>">
	<div class="arrow"></div>
	<h3>Yes, really reset their password</h3>
</a>
