<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";
$PARENT = isset($_POST["PARENT"]) ? $_POST["PARENT"] : null;

#Validate that the parent can be null or a number greater than 0
if(!($PARENT == null || is_numeric($PARENT) && $PARENT > 0)) {
	showError("Whoops", "I didn't quite understand the request...", "Sorry about that!", 400);
}

#Get parent information
$stmt = $conn->prepare(<<<SQL
SELECT NUM, TEACHER_NUM, PARENT_NUM, SYMBOL, NAME, DESCRIPTION
FROM COMPONENT
WHERE TEACHER_NUM = :teacherNum AND NUM = :componentNum
SQL
);
$stmt->execute(array('teacherNum' => $_SESSION["NUM"], 'componentNum' => $PARENT));
$count = $stmt->rowCount();
$parentdata = $stmt->fetch();

#Check to see if there is really a parent!
if($count != 1) {
	
#Ok, no parent matches. We'll act as if we are creating a new root component then.
?>
<div class="object subtitle"><h2>Create a new <br>"Root Component"</h2></div>
<div class="editor">
	<label for="componentname">Name: </label>
	<input id="componentname" type="text" name="NAME" placeholder="AP Computer Science"><br>
	<label for="description">Description: </label>
	<textarea id="description" name="DESCRIPTION" placeholder="College level computer science curriculum." rows="8"></textarea><br>
	<label for="symbol">Symbol: </label>
	<input id="symbol" type="text" name="TERM" placeholder="CIS, HIS, PSY, ENG, MATH, etc..."><br>
</div>
<a id="js_component_create_submit" class="object create" href="#"><div class="arrow"></div><h1>Submit</h1></a>
<?php	
} else {
	
#Nailed a match, go ahead and give the user some information about creating sub components.
?>
<div class="object subtitle"><h2>"<?php echo htmlentities($parentdata["NAME"]);?>"<br> sub component</h2></div>
<div class="editor">
	<label for="componentname">Name: </label>
	<input id="componentname" type="text" name="NAME" placeholder="Derivitives"><br>
	<label for="description">Description: </label>
	<textarea id="description" name="DESCRIPTION" placeholder="General understanding of limits, derivitives, formulas, and their application." rows="8"></textarea><br>
	<label for="symbol">Symbol: </label>
	<input id="symbol" type="text" name="TERM" placeholder="IV, Chapter 1, A, b, etc."><br>
</div>
<a id="js_component_create_submit" class="object create" href="#" data-num="<?php echo $parentdata["NUM"]; ?>"><div class="arrow"></div><h1>Submit</h1></a>
<?php
}
?>