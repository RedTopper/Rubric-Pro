<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";
?>
<div class="object subtitle">
	<h2>Class editor</h2>
</div>
<a id="js_classes_create" class="object create" href="#"><div class="arrow"></div><h1>Create new class</h1></a>
<?php
$stmt = $conn->prepare(
<<<SQL
SELECT NUM, TEACHER_NUM, NAME, YEAR, PERIOD, DESCRIPTOR
FROM CLASS
WHERE
TEACHER_NUM = :teacherID
ORDER BY YEAR DESC
SQL
);
$stmt->execute(array('teacherID' => $_SESSION["NUM"]));	
$data = $stmt->fetchAll();
foreach($data as $row) { 
?>
<a class="js_classes_edit object selectable" href="#" data-num="<?php echo $row["NUM"] ?>">
<div class="arrow"></div>
<h1>
<?php 

//Outpus the classes
echo "[" . 
htmlentities($row["NAME"]) . "]: Year " . 
htmlentities($row["YEAR"]) . ", " . 
htmlentities($row["PERIOD"]) . 
htmlentities(($row["DESCRIPTOR"] !== "" ? " (" . $row["DESCRIPTOR"] . ") " : " ")); 
?>
</h1>
</a>
<?php 
}
?>