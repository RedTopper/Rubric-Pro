<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";
$NUM = isset($_POST["NUM"]) ? $_POST["NUM"] : "";
$USERNAME = isset($_POST["USERNAME"]) ? $_POST["USERNAME"] : "";

#Check match
$stmt = $conn->prepare("SELECT NUM, USERNAME FROM STUDENT WHERE USERNAME = :username AND NUM = :num");
$stmt->execute(array('username' => $USERNAME, 'num' => $NUM));
$row = $stmt->fetch();

#We didn't get a result!?!?!?!?
if($stmt->rowCount() == 0) {
	showError("Error!", "Your client lied!", "I thought I trusted you! (The number and username submitted does not match)", 400);
}

#Ok, so the submitted number is the same as the username (didn't really need to check that but better safe then sorry?)
#We need to bind the submitted user ID to this teacher account.
$stmt = $conn->prepare(<<<SQL
INSERT INTO TEACHES
(TEACHER_NUM, STUDENT_NUM)
VALUES
(:teacher, :student)
SQL
);
$stmt->execute(array('teacher' => $_SESSION['NUM'], 'student' => $NUM));

#Redirect using some Javascript Hackery(tm)
header("JS-Redirect: account");

#It's not really an error, but it does the same thing.
showError("Ok!", "The acccount has been linked.", "We'll automatically redirect you now...", 201);
?>