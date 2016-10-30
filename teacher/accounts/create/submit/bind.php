<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsSQL = true;
include "../../../../restricted/db.php";
include "../../../../restricted/sql.php";

$STUDENT_NUM = isset($_POST["STUDENT_NUM"]) ? $_POST["STUDENT_NUM"] : "";
$USERNAME = isset($_POST["USERNAME"]) ? $_POST["USERNAME"] : "";

#We didn't get a result!?!?!?!?
if(!sql_doesStudentUsernameAndNumberMatch($USERNAME, $STUDENT_NUM)) {
	db_showError("Error!", "Your client lied!", "I thought I trusted you! (The number and username submitted does not match)", 400);
}

#Ok, so the submitted number is the same as the username (didn't really need to check that but better safe then sorry?)
#We need to bind the submitted user ID to this teacher account.
sql_bindStudentToTeacher($STUDENT_NUM, $_SESSION['NUM']);

#Redirect using some Javascript Hackery(tm)
header("JS-Redirect: account");

#It's not really an error, but it does the same thing.
db_showError("Ok!", "The acccount has been linked.", "", 201);
?>