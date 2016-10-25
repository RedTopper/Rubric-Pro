<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsSQL = true;
include "../../restricted/db.php";
include "../../restricted/sql.php";

$PARENT = isset($_POST["PARENT"]) ? $_POST["PARENT"] : null;
$SYMBOL = isset($_POST["SYMBOL"]) ? $_POST["SYMBOL"] : "";
$NAME = isset($_POST["NAME"]) ? $_POST["NAME"] : "";
$DESCRIPTION = isset($_POST["DESCRIPTION"]) ? $_POST["DESCRIPTION"] : "";

if(strlen($NAME) < 2) {
	db_showError("Error creating component!", "The name needs to be at least 2 letters!", "Please type a longer name.", 400);
}

if(strlen($SYMBOL) < 1) {
	db_showError("Error creating component!", "The symbol needs to be at least 1 letter!", "Please type a longer symbol.", 400);
}

#Validate that the parent can be null or a number greater than 0
if(!($PARENT == null || is_numeric($PARENT) && $PARENT > 0)) {
	db_showError("Whoops", "I didn't quite understand the request...", "Sorry about that!", 400);
}

#Get parent information
$parent = sql_getComponent($_SESSION["NUM"], $PARENT);

if($parent === null) {

	#This is a root component we are creating because the parent does not exist.
	sql_createRootComponent($_SESSION["NUM"], $SYMBOL, $NAME, $DESCRIPTION);
} else {
	
	#Otherwise we are creating a new component as a part of the parent we just found.
	sql_createComponent($_SESSION["NUM"], $parent["NUM"], $SYMBOL, $NAME, $DESCRIPTION);
}

header("JS-Redirect: removeto-3");

#Show that it's been created
db_showError("Ok!", "Created the component \"" . htmlentities($NAME) . "\".", "", 201);
?>