<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";
$STUDENT = isset($_POST["STUDENT"]) ? $_POST["STUDENT"] : "";

//Selecting of the password is only to show if the student has changed their password or not.
$stmt = $conn->prepare( 
<<<SQL
SELECT STUDENT.NUM, STUDENT.USERNAME, STUDENT.PASSWORD, STUDENT.FIRST_NAME, STUDENT.LAST_NAME, STUDENT.NICK_NAME, STUDENT.GRADE, STUDENT.EXTRA
FROM STUDENT
JOIN TEACHES ON STUDENT.NUM = TEACHES.STUDENT_NUM
JOIN TEACHER ON TEACHES.TEACHER_NUM = :teacherID
WHERE STUDENT.NUM = :student
SQL
);
$stmt->execute(array('teacherID' => $_SESSION["NUM"], 'student' => $STUDENT));
$count = $stmt->rowCount();
if($count == 1) {
	$row = $stmt->fetch();
?>
<div class="object subtitle">
	<h2>Editing: <?php echo htmlentities($row["LAST_NAME"]) . ", " . htmlentities($row["FIRST_NAME"]); ?></h2>
</div>
<div class="object subtext">
	<p>Username: <?php echo htmlentities($row["USERNAME"]); ?>.
	<p>Password: <?php echo ($row["PASSWORD"] == "CHANGE" ? "No password set" : "Yes");  ?>.
	<p>Nick Name: <?php if($row["NICK_NAME"] != "") {echo htmlentities($row["NICK_NAME"]);} else {echo "None given";} ?>.
	<p>Grade level: <?php echo $row["GRADE"]; ?>.
	<p>Extra information: <?php if($row["EXTRA"] != "") {echo htmlentities($row["EXTRA"]);} else {echo "None given";} ?>.
</div>
<?php
if($row["PASSWORD"] != "CHANGE") {
?>
<a class="object create" href="#"><div class="arrow"></div><h1>Reset password</h1></a>
<?php
}
?>
<div class="object subtitle">
	<h2>Danger zone!</h2>
</div>
<div class="object subtext">
	<p>You have been warned!
</div>
<a class="object destroy" href="#"><div class="arrow"></div><h1>Destroy account forever (a long time!)</h1></a>
<?php
} else {
	showError("Whoops!", "Something went wrong when requesting that student.", "Check to see if the student still exists and is linked to your account.", 400);
}
?>