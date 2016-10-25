<?php
$CLASS = isset($_POST["CLASS"]) ? $_POST["CLASS"] : "";

include "../../restricted/view_verify.php";

###################################

if($CLASS == "") {
	showError("Whoops!", "You didn't select a class!", "Try selecting a class first.", 400);
}

#Check ownership
$stmt = $conn->prepare("SELECT NUM, TEACHER_NUM, NAME FROM CLASS WHERE TEACHER_NUM = :teacherNum AND NUM = :classNum");
$stmt->execute(array('teacherNum' => $_SESSION["NUM"], 'classNum' => $CLASS));
$classcount = $stmt->rowCount();
if($classcount != 1) {
	showError("Whoops!", "You can't add a student to a class that doesn't belong to you!", "Try selecting another class.", 400);
}

#Get the name of the class.
$classname = $stmt->fetch();

#Check duplicates
$stmt = $conn->prepare("SELECT STUDENT_NUM, CLASS_NUM FROM `CLASS-STUDENT_LINKER` WHERE STUDENT_NUM = :studentnum AND CLASS_NUM = :classnum");
$stmt->execute(array('studentnum' => $STUDENT, 'classnum' => $CLASS));
$linkcount = $stmt->rowCount();

#If there is a duplicate, deny it.
#We don't need 10,000 of the same entry
if($linkcount > 0) {
	showError("Whoops!", "That student already belongs in that class!", "Try selecting another class or student.", 400);
}

#Use access.js to clear all things after tier 1 (so the user doesn't loose their search)
header("JS-Redirect: removeto1");

#Bind!
$stmt = $conn->prepare("INSERT INTO `CLASS-STUDENT_LINKER` (STUDENT_NUM, CLASS_NUM) VALUES (:studentnum, :classnum)");
$stmt->execute(array('studentnum' => $STUDENT, 'classnum' => $CLASS)); #We've already verified the number is correct.

#Show that it's been bound
showError("Ok!", "The acccount has been bound to " . htmlentities($classname["NAME"]) . ".", "", 201);