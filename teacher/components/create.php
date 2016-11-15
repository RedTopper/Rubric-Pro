<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsSQL = true;
include "../../restricted/db.php";
include "../../restricted/sql.php";

$PARENT_NUM = isset($_POST["PARENT_NUM"]) ? $_POST["PARENT_NUM"] : null;

#Validate that the parent can be null or a number greater than 0
if(!($PARENT_NUM == null || is_numeric($PARENT_NUM) && $PARENT_NUM > 0)) {
	db_showError("Whoops", "I didn't quite understand the request...", "Sorry about that!", 400);
}

#Get parent information
$parent = sql_getComponent($_SESSION["NUM"], $PARENT_NUM);
$number = count(sql_getAllSubComponentsFromComponent($_SESSION["NUM"], $PARENT_NUM)) + 1;

#Check to see if there is really a parent!
if($parent === null) {
	
	#Ok, no parent matches. We'll act as if we are creating a new root component then. ?>
	<div class="object subtitle">
		<h2>Create a new <br>"Root Component"</h2>
	</div>
	<div class="editor">
		<label for="componentname">Name: </label>
		<input id="componentname" type="text" name="NAME" placeholder="AP Computer Science"><br>
		<label for="symbol">Symbol: </label>
		<input id="symbol" type="text" name="TERM" placeholder="CIS, HIS, PSY, ENG, MATH, etc..."><br>
		<label for="description">Description: </label>
		<textarea id="description" name="DESCRIPTION" placeholder="College level computer science curriculum." rows="8"></textarea><br>
	</div>
	<a id="js_components_create_submit" class="object create" href="#"><div class="arrow"></div><h3>Submit</h3></a><?php	
} else {
	
	#Nailed a match, go ahead and give the user some information about creating sub components. ?>
	<div class="object subtitle"><h2>"<?php echo htmlentities($parent["NAME"]);?>"<br> sub component</h2></div>
	<div class="editor">
		<label for="componentname">Name: </label>
		<input id="componentname" type="text" name="NAME" placeholder="Derivitives"><br>
		<label for="symbol">Symbol: </label>
		<input id="symbol" type="text" name="TERM" placeholder="IV, Chapter 1, A, b, etc." value="<?php echo $number ?>"><br>
		<label for="description">Description: </label>
		<textarea id="description" name="DESCRIPTION" placeholder="General understanding of limits, derivitives, formulas, and their application." rows="8"></textarea><br>
	</div>
	<a id="js_components_create_submit" class="object create" href="#" data-parentnum="<?php echo $parent["NUM"]; ?>"><div class="arrow"></div><h3>Submit</h3></a><?php
}