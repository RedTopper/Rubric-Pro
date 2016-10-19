<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";
$SUBTITLE = isset($_POST["SUBTITLE"]) ? $_POST["SUBTITLE"] : null;
$MAX_POINTS_PER_CRITERIA = isset($_POST["MAX_POINTS_PER_CRITERIA"]) ? $_POST["MAX_POINTS_PER_CRITERIA"] : "";

#Validate length of rubric name.
if(strlen($SUBTITLE) < 2) {
	showError("Error creating rubric!", "The title needs to be at least 2 letters!", "Please type a longer title.", 400);
}

#Validate that the points are actually numbers.
if(!(is_numeric($MAX_POINTS_PER_CRITERIA) && $MAX_POINTS_PER_CRITERIA >= 0)) {
	showError("Error creating rubric!", "The points per criteria needs to be a number greater than 0.", "Sorry about that!", 400);
}

#Write to database.
$stmt = $conn->prepare(<<<SQL
INSERT INTO RUBRIC
(TEACHER_NUM, MAX_POINTS_PER_CRITERIA, SUBTITLE)
VALUES
(:teacherNum, :points, :sub)
SQL
);
$stmt->execute(array('teacherNum' => $_SESSION["NUM"], 
					 'points' => $MAX_POINTS_PER_CRITERIA,
					 'sub' => $SUBTITLE));

header("JS-Redirect: rubrics");

#Show that it's been created
showError("Ok!", "Created the rubric \"" . htmlentities($SUBTITLE) . "\".", "", 201);
?>