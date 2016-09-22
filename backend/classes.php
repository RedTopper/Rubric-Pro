<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";

$needsFunction = true;
include "functions.php";
?>
<div class="object subtitle">
	<h2>Your classes</h2>
</div>
<a id="js_classes_create" class="object create" href="#"><div class="arrow"></div><h1>Create new class</h1></a>
<?php

$stmt = $conn->prepare(
<<<SQL
SELECT NUM, TEACHER_NUM, NAME, YEAR, TERM, PERIOD, DESCRIPTOR
FROM CLASS
WHERE
TEACHER_NUM = :teacherID
ORDER BY YEAR DESC, TERM DESC, PERIOD
SQL
);
$stmt->execute(array('teacherID' => $_SESSION["NUM"]));	
$data = $stmt->fetchAll();
listclasses("js_classes_edit", $data);
?>