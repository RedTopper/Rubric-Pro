<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsSQL = true;
include "../../../restricted/db.php";
include "../../../restricted/sql.php";

$USERNAME = isset($_POST["USERNAME"]) ? $_POST["USERNAME"] : "";
$LAST_NAME = isset($_POST["LAST_NAME"]) ? $_POST["LAST_NAME"] : "";
$FIRST_NAME = isset($_POST["FIRST_NAME"]) ? $_POST["FIRST_NAME"] : "";
$NICK_NAME = isset($_POST["NICK_NAME"]) ? $_POST["NICK_NAME"] : "";
$GRADE = isset($_POST["GRADE"]) ? $_POST["GRADE"] : "";
$EXTRA = isset($_POST["EXTRA"]) ? $_POST["EXTRA"] : "";

#Check to see if the user actually typed anything.
if(strlen($USERNAME) < 2) {
	db_showError("Error creating account!", "The username is too short.", "Please type a longer username.", 400);
}

#We need to make sure that the username doesn't already exist in BOTH tables (so we don't mistake a student for a teacher)
if(sql_isUsernameInTeacherDatabase($USERNAME)) {
	db_showError("Error creating account!", "The username cannot be the username of another teacher!", "Please change the username field to something else.", 400);
}

$studentNum = null;
if(sql_isUsernameInStudentDatabase($USERNAME, $studentNum)) {
	
	#Ok, so unfortunately we have a match, we figure out how to deal with it.
	
	#Check if there is already a link between the teacher and the student
	if(sql_isTeacherAndStudentLinked($_SESSION["NUM"], $studentNum)) {
		db_showError("Error creating account!", "The username cannot be the username of another student that's already bound to your account!", "Please change the username field to something else.", 400);
	}
	
	$student = sql_getStudentInformation($studentNum); ?>
	
	<div class="object subtitle">
		<h2>We found a matching username!</h2>
		<h2>Is <?php echo htmlentities($student["LAST_NAME"]) . ", " . htmlentities($student["FIRST_NAME"]); ?> the student you are looking for?</h2>
	</div>

	<div class="object subtext">
		<p>Username: <?php echo htmlentities($student["USERNAME"]); ?>.
		<p>Password: <?php echo ($student["PASSWORD"] == "CHANGE" ? "No password set" : "Yes");  ?>.
		<p>Nick Name: <?php if($student["NICK_NAME"] != "") {echo htmlentities($student["NICK_NAME"]);} else {echo "None given";} ?>.
		<p>Grade level: <?php echo $student["GRADE"]; ?>.
		<p>Extra information: <?php if($student["EXTRA"] != "") {echo htmlentities($student["EXTRA"]);} else {echo "None given";} ?>.
	</div>

	<div class="object subtitle">
		<h2>Notice</h2>
	</div>
	<div class="object subtext">
		<p>If this is <b>NOT</b> the student you are looking for, edit the fields to the left, then submit the request again.
	</div>

	<a id="js_accounts_create_submit_bind" class="object create" href="#" data-studentnum="<?php echo $studentNum; ?>" data-username="<?php echo htmlentities($student["USERNAME"]); ?>">
		<div class="arrow"></div>
		<h3>Bind Accounts</h3>
	</a>
	<?php
	die();
} 

if(strlen($LAST_NAME) < 2 || strlen($FIRST_NAME) < 2) {
	db_showError("Error creating account!", "The name is too short.", "Please type a longer first name or last name.", 400);
}

if(strlen($GRADE) < 1 || !is_numeric($GRADE)) {
	db_showError("Error creating account!", "The grade a student is in must be a number.", "Check what you typed, then try again.", 400);
}

#Ok, so far so good. Now we need to insert the new account.
sql_createStudent($USERNAME, $FIRST_NAME, $LAST_NAME, $NICK_NAME, $GRADE, $EXTRA);
$insertedStudent = $conn->lastInsertId();

#And link them together.
sql_bindStudentToTeacher($insertedStudent, $_SESSION["NUM"]);

#Redirect using some Javascript Hackery(tm)
header("JS-Redirect: account");

#It's not really an error, but it does the same thing.
db_showError("Ok!", "The acccount has been created.", "", 201);
?>