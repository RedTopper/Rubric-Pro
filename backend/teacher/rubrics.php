<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";

$needsFunction = true;
include "functions.php";
?>
<div class="object subtitle">
	<h2>Your Rubrics</h2>
</div>
<a id="js_rubrics_create" class="object create" href="#"><div class="arrow"></div><h3>Create new rubric</h3></a>
<?php
$stmt = $conn->prepare(
<<<SQL
SELECT NUM, TEACHER_NUM, MAX_POINTS_PER_CRITERIA, SUBTITLE, ((
	SELECT COUNT(*) 
	FROM RUBRIC_CRITERIA
	WHERE
	TEACHER_NUM = :teacherID AND
	RUBRIC_NUM = RUBRIC.NUM) * MAX_POINTS_PER_CRITERIA) AS TOTAL_POINTS 
FROM RUBRIC
WHERE
TEACHER_NUM = :teacherID
ORDER BY SUBTITLE
SQL
);
$stmt->execute(array('teacherID' => $_SESSION["NUM"]));	
$data = $stmt->fetchAll();

$countRubrics = $stmt->rowCount();

if($countRubrics === 0) {
?>
<div class="object subtitle"><h2>Hey!</h2></div>
<div class="object subtext">
	<p>Looks like you don't have any rubrics yet. Try creating one with the button above!</p>
</div>
<?php
} else {
	listRubrics("js_rubrics_select", $data);
}