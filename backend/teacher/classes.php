<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "../restricted/db.php";

$needsFunction = true;
include "../restricted/functions.php";
?>
<div class="object subtitle">
	<h2>Your classes</h2>
</div>
<a id="js_classes_create" class="object create" href="#"><div class="arrow"></div><h3>Create new class</h3></a>
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