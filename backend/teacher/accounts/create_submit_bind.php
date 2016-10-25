<?php
$NUM = isset($_POST["NUM"]) ? $_POST["NUM"] : "";
$USERNAME = isset($_POST["USERNAME"]) ? $_POST["USERNAME"] : "";

#Initialize db.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "../../restricted/db.php";

#Include SQL functions
$needsSQL = true;
include "../../restricted/sql.php";

#We didn't get a result!?!?!?!?
if(doesStudentUsernameAndNumberMatch($USERNAME, $NUM)) {
	showError("Error!", "Your client lied!", "I thought I trusted you! (The number and username submitted does not match)", 400);
}

#Ok, so the submitted number is the same as the username (didn't really need to check that but better safe then sorry?)
#We need to bind the submitted user ID to this teacher account.
linkTeacherToStudent($_SESSION['NUM'], $NUM);

#Redirect using some Javascript Hackery(tm)
header("JS-Redirect: account");

#It's not really an error, but it does the same thing.
showError("Ok!", "The acccount has been linked.", "", 201);
?>