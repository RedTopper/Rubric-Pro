<?php
if(!isset($needsFunction)) die();

function listclasses($classname) {
	global $conn;
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
	foreach($data as $row) { 
?>
	<a class="<?php echo $classname; ?> object selectable" href="#" data-num="<?php echo $row["NUM"] ?>">
	<div class="arrow"></div>
	<h1>
<?php 

	//Outpus the classes
	echo "[" . 
	htmlentities($row["NAME"]) . "]:<br> Year " . 
	$row["YEAR"] . ", Term " . 
	$row["TERM"] . ", " . 
	htmlentities($row["PERIOD"]) . 
	($row["DESCRIPTOR"] !== "" ? " <br><div class='monospace'>(" . htmlentities($row["DESCRIPTOR"]) . ")</div> " : " "); 
?>
	</h1>
	</a>
<?php 
	}
}
?>