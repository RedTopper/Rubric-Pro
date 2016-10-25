<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";
$CLASS = isset($_POST["CLASS"]) ? $_POST["CLASS"] : "";

#General global functions
$needsFunction = true;
include "functions.php";

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
	
	#Show general information
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
	<?php
	
	#Project management section
	?>
	<div class="object subtitle">
		<h2>Project management:</h2>
	</div>
	<a id="js_classes_edit_viewprojects" class="object create" href="#"><div class="arrow"></div>
		<h3>View</h3>
	</a>
	<a id="js_classes_edit_createprojects" class="object create" href="#"><div class="arrow"></div>
		<h3>Create</h3>
	</a>
	<a id="js_classes_edit_destroyprojects" class="object destroy" href="#"><div class="arrow"></div>
		<h3>Destroy</h3>
	</a>
	<?php
	
	#Students that belong to this class
	?>
	<div class="object subtitle">
		<h2>Current members:</h2>
	</div>
	<?php
	
	#Gets a list of students in a class that belongs to the logged in teacher.
	$stmt = $conn->prepare(
<<<SQL
SELECT STUDENT.NUM, STUDENT.USERNAME, STUDENT.FIRST_NAME, STUDENT.LAST_NAME, STUDENT.NICK_NAME, STUDENT.GRADE, STUDENT.EXTRA
FROM STUDENT, `CLASS-STUDENT_LINKER` CSL
WHERE
CSL.CLASS_NUM = :classNum AND 
CSL.STUDENT_NUM = STUDENT.NUM
ORDER BY STUDENT.LAST_NAME, STUDENT.FIRST_NAME
SQL
	);
	$stmt->execute(array('classNum' => $row["NUM"]));
	$countStudents = $stmt->rowCount();
	
	if($countStudents > 0) {
		$students = $stmt->fetchAll();
		
		#Display students. Last false means not selectable btw
		listStudents("idkbro", $students, false);
		?>
		</div>
			<div class="object subtext">
			<p>You can add and remove more students throught the "Accounts" tab.
		</div>
		<?php
	} else {
		?>
		</div>
			<div class="object subtext">
			<p>No students belong to this class. Try adding some through the accounts tab.
		</div>
		<?php
	}
	?>
<?php
} else {
	db_showError("Whoops!", "Something went wrong when requesting that class.", "Check to see if you are the owner of that class, or if your client sent the wrong class ID.", 400);
}
?>