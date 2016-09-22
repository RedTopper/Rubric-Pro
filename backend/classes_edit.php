<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";
$CLASS = isset($_POST["CLASS"]) ? $_POST["CLASS"] : "";

$stmt = $conn->prepare(
<<<SQL
SELECT NUM, TEACHER_NUM, NAME, YEAR, TERM, PERIOD, DESCRIPTOR
FROM CLASS
WHERE
TEACHER_NUM = :teacherID AND NUM = :class
SQL
);
$stmt->execute(array('teacherID' => $_SESSION["NUM"],
					 'class' => $CLASS));	
$count = $stmt->rowCount();

#Check to see if there is really a student!
if($count == 1) {
	$row = $stmt->fetch();
?>
	<div class="object subtitle">
		<h2><?php echo htmlentities($row["NAME"]); ?></h2>
	</div>
		<div class="object subtext">
		<p><?php echo "Year: " . $row["YEAR"]; ?>.
		<p><?php echo "Term: " . $row["TERM"]; ?>.
		<p><?php echo htmlentities($row["PERIOD"]); ?>.
		<p><?php echo ($row["DESCRIPTOR"] !== "" ? 
			"Description: " . htmlentities($row["DESCRIPTOR"]) . "." : 
			"");  ?>
	</div>
	<div class="object subtitle">
		<h2>Project management:</h2>
	</div>
	<a id="js_classes_edit_viewprojects" class="object create" href="#"><div class="arrow"></div>
		<h1>View</h1>
	</a>
	<a id="js_classes_edit_createprojects" class="object create" href="#"><div class="arrow"></div>
		<h1>Create</h1>
	</a>
	<a id="js_classes_edit_removeprojects" class="object destroy" href="#"><div class="arrow"></div>
		<h1>Remove</h1>
	</a>
	<div class="object subtitle">
		<h2>Student management:</h2>
	</div>
	<a id="js_classes_edit_viewstudents" class="object create" href="#"><div class="arrow"></div>
		<h1>View</h1>
	</a>
	</div>
		<div class="object subtext">
		<p>Add or remove students from this class through the Accounts tab.
	</div>
<?php
} else {
		showError("Whoops!", "Something went wrong when requesting that class.", "Check to see if you are the owner of that class, or if your client sent the wrong class ID.", 400);
}
?>