<?php
$needsAuthentication = true;
$needsAJAX = true;
include "db.php";

$USERNAME = isset($_POST["USERNAME"]) ? $_POST["USERNAME"] : "";
$LAST_NAME = isset($_POST["LAST_NAME"]) ? $_POST["LAST_NAME"] : "";
$FIRST_NAME = isset($_POST["FIRST_NAME"]) ? $_POST["FIRST_NAME"] : "";
$NICK_NAME = isset($_POST["NICK_NAME"]) ? $_POST["NICK_NAME"] : "";
$GRADE = isset($_POST["GRADE"]) ? $_POST["GRADE"] : "";
$EXTRA = isset($_POST["EXTRA"]) ? $_POST["EXTRA"] : "";

#check to see if the user actually typed anything.
if(strlen($USERNAME) < 2 || strlen($LAST_NAME) < 2 || strlen($FIRST_NAME) < 2) {
	showError("Error creating account!", "Either the username, last name, or first name was too short.", "Please make sure all fields are long enough, then try again.", 400);
} 

if(strlen($GRADE) < 1 || !is_numeric($GRADE)) {
	showError("Error creating account!", "The grade a student is in must be a number.", "Check what you typed, then try again.", 400);
}

#We need to make sure that the username doesn't already exist in BOTH tables (so we don't mistake a student for a teacher)
$stmt = $conn->prepare("SELECT USERNAME FROM TEACHER WHERE USERNAME = :username");
$stmt->execute(array('username' => $USERNAME));
$row = $stmt->fetchAll();

#Check teachers
if($stmt->rowCount() > 0) {
	showError("Error creating account!", "The username cannot be the username of another teacher!", "Please change the username field to something else.", 400);
}

#Check students
$stmt = $conn->prepare("SELECT USERNAME FROM STUDENT WHERE USERNAME = :username");
$stmt->execute(array('username' => $USERNAME));
$row = $stmt->fetchAll();
	
if($stmt->rowCount() > 0) {
	showError("Error creating account!", "The username cannot be the username of another student!", "Please change the username field to something else.", 400);
}

#Ok, so far so good. Now we need to insert the new account.
$stmt = $conn->prepare(<<<SQL
INSERT INTO STUDENT 
(USERNAME, PASSWORD, FIRST_NAME, LAST_NAME, NICK_NAME, GRADE, EXTRA, SETTINGS)
VALUES
(:username, :password, :first, :last, :nick, :grade, :extra, :settings)
SQL
);
$stmt->execute(array('username' => $USERNAME, 'password' => "CHANGE", 'first' => $FIRST_NAME, 'last' => $LAST_NAME, 'nick' => $NICK_NAME, 'grade' => $GRADE, 'extra' => $EXTRA, 'settings' => "{}"));
$insertedStudent = $conn->lastInsertId();

#Ok, so we have the new account, so we need to insert it into the 
$stmt = $conn->prepare(<<<SQL
INSERT INTO TEACHES
(TEACHER_NUM, STUDENT_NUM)
VALUES
(:teacher, :student)
SQL
);
$stmt->execute(array('teacher' => $_SESSION['NUM'], 'student' => $insertedStudent));

#Redirect using some Javascript Hackery(tm)
header("JS-Redirect: account");

#It's not really an error, but it does the same thing.
showError("Ok!", "The acccount has been created.", "We'll automatically redirect you now...", 201);
?>