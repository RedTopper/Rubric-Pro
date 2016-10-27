<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsSQL = true;
include "../../../restricted/db.php";
include "../../../restricted/sql.php";

$TITLE = isset($_POST["TITLE"]) ? $_POST["TITLE"] : null;
$DESCRIPTION = isset($_POST["DESCRIPTION"]) ? $_POST["DESCRIPTION"] : "";

#Validate length of rubric name.
if(strlen($TITLE) < 2) {
	db_showError("Error creating assignment!", "The title needs to be at least 2 letters!", "Please type a longer title.", 400);
}

#Write to database.
sql_createAssignment($_SESSION["NUM"], $TITLE, $DESCRIPTION);

header("JS-Redirect: assignment");

#Show that it's been created
db_showError("Ok!", 'Created the assignment "' . htmlentities($TITLE) . '".', "", 201);