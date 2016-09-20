<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";
$STUDENT = isset($_POST["STUDENT"]) ? $_POST["STUDENT"] : "";
$REQUEST = isset($_POST["REQUEST"]) ? $_POST["REQUEST"] : "";

#Selecting of the password is only to show if the student has changed their password or not.
$stmt = $conn->prepare( 
<<<SQL
SELECT STUDENT.NUM, STUDENT.USERNAME, STUDENT.PASSWORD, STUDENT.FIRST_NAME, STUDENT.LAST_NAME, STUDENT.NICK_NAME, STUDENT.GRADE, STUDENT.EXTRA
FROM STUDENT
JOIN TEACHES ON STUDENT.NUM = TEACHES.STUDENT_NUM
WHERE 
TEACHES.TEACHER_NUM = :teacherID AND
STUDENT.NUM = :student
SQL
);
$stmt->execute(array('teacherID' => $_SESSION["NUM"], 'student' => $STUDENT));
$count = $stmt->rowCount();

#Check to see if there is really a student!
if($count == 1) {
	$row = $stmt->fetch();
	
	#Check how we are viewing the student.
	switch ($REQUEST) {
		
		#ACTUALLY unbind the user's account!
		case "UNBIND":
			#Use access.js to redirect the user back to their accounts after some time.
			header("JS-Redirect: account");
			
			#Unbind!
			$stmt = $conn->prepare("DELETE FROM TEACHES WHERE STUDENT_NUM=:num");
			$stmt->execute(array('num' => $row["NUM"])); #We've already verified the number is correct.
			
			#Show that it's been unbound
			showError("Ok!", "The acccount has been unbound.", "We'll automatically redirect you now...", 201);
			break;
			
			
			
			
		
		#ASK to unbind the account from the teacher
		case "UNBIND-ASK":
			?>
			<div class="object subtitle">
				<h2>Really unbind <?php echo  htmlentities($row["FIRST_NAME"]) . " " . htmlentities($row["LAST_NAME"]); ?> from your account?</h2>
			</div>
			<div class="object subtext">
				<p>Here's what'll happen:
				<p>The student WILL have access to grades from your class
				<p>The student WILL effect your graded criteria
				<p>The student WILL be able to view their rubrics
				<p>The student CAN be added back to this list
				<p>The student CAN be added to other teachers classes
				<p>The student WILL NOT be deleted forever
				<p>The student WILL NOT appear in this list any more!
				<p>This operation makes it easy for YOU to manage your students! Unbinding a student from your account is NOT a dangerous operation, so it's reccomended to unbind a student at the end of the term or year!
			</div>
			<a id="js_accounts_student_unbind_yes" class="object destroy" href="#" data-num="<?php echo $row["NUM"]; ?>"><div class="arrow"></div><h1>Yes, unbind <?php echo  htmlentities($row["FIRST_NAME"]) . " " . htmlentities($row["LAST_NAME"]); ?></h1></a>
			<?php
			break;
		
		
		
		
		
		#ACTUALLY reset the user's password!
		case "RESET":
			header("JS-Redirect: account");
			
			#Do the reset!
			$stmt = $conn->prepare("UPDATE STUDENT SET PASSWORD='CHANGE' WHERE NUM=:num");
			$stmt->execute(array('num' => $row["NUM"]));

			#Show that it's been reset
			showError("Ok!", "The acccount has been reset.", "We'll automatically redirect you now...", 201);
			break;
		
		
		
		
		
		#ASK to reset the password!
		case "RESET-ASK":
			?>
			<div class="object subtitle">
				<h2>Really reset <?php echo  htmlentities($row["FIRST_NAME"]) . " " . htmlentities($row["LAST_NAME"]); ?>'s password?</h2>
			</div>
			<div class="object subtext">
				<p>They'll be able to set a new password the next time they log in.
			</div>
			<a id="js_accounts_student_reset_yes" class="object destroy" href="#" data-num="<?php echo $row["NUM"]; ?>"><div class="arrow"></div><h1>Yes, really reset their password</h1></a>
			<?php
			break;
		
		
		
		
		
		#VIEW of the students (and the default if all else fails)
		case "VIEW":
		default:
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

			#Do not display the ability to reset the password if the password is reset already!
			if($row["PASSWORD"] != "CHANGE") { ?>
			<a id="js_accounts_student_reset" class="object create" href="#" data-num="<?php echo $row["NUM"]; ?>"><div class="arrow"></div><h1>Reset password</h1></a>
			<?php 
			}

			#Finally, add the ability to unbind the account from the teacher.
			?>
			<a id="js_accounts_student_unbind" class="object destroy" href="#" data-num="<?php echo $row["NUM"]; ?>"><div class="arrow"></div><h1>Unbind account</h1></a>


			<?php
			break;
	}
	
#If we do not find the student, then the teacher was trying to access something they should not!
} else {
	showError("Whoops!", "Something went wrong when requesting that student.", "Check to see if the student still exists and is linked to your account.", 400);
}
?>