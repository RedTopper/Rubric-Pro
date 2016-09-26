<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";
$PARENT = isset($_POST["PARENT"]) ? $_POST["PARENT"] : null;
$SYMBOL = isset($_POST["SYMBOL"]) ? $_POST["SYMBOL"] : "";
$NAME = isset($_POST["NAME"]) ? $_POST["NAME"] : "";
$DESCRIPTION = isset($_POST["DESCRIPTION"]) ? $_POST["DESCRIPTION"] : "";

if(strlen($NAME) < 2) {
	showError("Error creating component!", "The name needs to be at least 2 letters!", "Please type a longer name.", 400);
}

if(strlen($DESCRIPTION) < 5) {
	showError("Error creating component!", "The description needs to be at least 5 letters!", "Please type a longer description.", 400);
}

if(strlen($SYMBOL) < 1) {
	showError("Error creating component!", "The symbol needs to be at least 1 letter!", "Please type a longer symbol.", 400);
}

#Validate that the parent can be null or a number greater than 0
if(!($PARENT == null || is_numeric($PARENT) && $PARENT > 0)) {
	showError("Whoops", "I didn't quite understand the request...", "Sorry about that!", 400);
}

#Get parent information
$stmt = $conn->prepare(<<<SQL
SELECT NUM, TEACHER_NUM, PARENT_NUM
FROM COMPONENT
WHERE TEACHER_NUM = :teacherNum AND NUM = :componentNum
SQL
);
$stmt->execute(array('teacherNum' => $_SESSION["NUM"], 'componentNum' => $PARENT));
$count = $stmt->rowCount();
$parentdata = $stmt->fetch();

#Check to see if there is really a parent!
if($count != 1) {

	#There is not... Add as a root component instead.
	$stmt = $conn->prepare(<<<SQL
INSERT INTO COMPONENT
(TEACHER_NUM, PARENT_NUM, SYMBOL, NAME, DESCRIPTION)
VALUES
(:teacherNum, NULL, :symbol, :name, :description)
SQL
);
$stmt->execute(array('teacherNum' => $_SESSION["NUM"], 
					 'symbol' => $SYMBOL, 
					 'name' => $NAME,
					 'description' => $DESCRIPTION));
} else {
	
	#There is! Write to database with the parent number!
	$stmt = $conn->prepare(<<<SQL
INSERT INTO COMPONENT
(TEACHER_NUM, PARENT_NUM, SYMBOL, NAME, DESCRIPTION)
VALUES
(:teacherNum, :parentNum, :symbol, :name, :description)
SQL
);
$stmt->execute(array('teacherNum' => $_SESSION["NUM"], 
					 'parentNum' => $parentdata["NUM"],
					 'symbol' => $SYMBOL, 
					 'name' => $NAME,
					 'description' => $DESCRIPTION));
}

header("JS-Redirect: components");

#Show that it's been unbound
showError("Ok!", "Created the component \"" . htmlentities($NAME) . "\".", "We'll automatically redirect you now...", 201);
?>