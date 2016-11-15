<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsFunction = true;
$needsSQL = true;
include "../../../../restricted/db.php";
include "../../../../restricted/functions.php";
include "../../../../restricted/sql.php";

$CLASS_NUM = isset($_POST["CLASS_NUM"]) ? $_POST["CLASS_NUM"] : "";
$ASSIGNMENT_NUM = isset($_POST["ASSIGNMENT_NUM"]) ? $_POST["ASSIGNMENT_NUM"] : "";
$STUDENT_NUM = isset($_POST["STUDENT_NUM"]) ? $_POST["STUDENT_NUM"] : ""; 

header("JS-Resize: auto"); ?>

 <div class="object subtitle">
	<h2>Choose your grade:</h2>
</div>

<?php
$rubrics = sql_getAllRubricsInAssignment($_SESSION["NUM"], $ASSIGNMENT_NUM);

if($rubrics == null) { 
	db_showError("Whoops!", "There are no rubrics to grade in this assignment", "Try adding a few first!", 500);	
}

foreach($rubrics as $rubric) {
	fun_gradeRubric($rubric["NUM"]);
}
?>
