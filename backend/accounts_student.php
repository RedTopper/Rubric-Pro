<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";
$STUDENT = isset($_POST["STUDENT"]) ? $_POST["STUDENT"] : "";
$CLASS = isset($_POST["CLASS"]) ? $_POST["CLASS"] : "";
$REQUEST = isset($_POST["REQUEST"]) ? $_POST["REQUEST"] : "";

#General global functions
$needsFunction = true;
include "functions.php";

####This file contains many of the features for managing student accounts. Please check
####the switch statement for details.
####Reason: the SQL statement below checks the existance of a student.

#Selecting of the password is only to show if the student has changed their password or not.
$stmt = $conn->prepare( 
<<<SQL
SELECT STUDENT.NUM, STUDENT.USERNAME, STUDENT.PASSWORD, STUDENT.FIRST_NAME, STUDENT.LAST_NAME, STUDENT.NICK_NAME, STUDENT.GRADE, STUDENT.EXTRA
FROM STUDENT
JOIN TEACHES ON STUDENT.NUM = TEACHES.STUDENT_NUM
WHERE 
TEACHES.TEACHER_NUM = :teacherNum AND
STUDENT.NUM = :student
SQL
);
$stmt->execute(array('teacherNum' => $_SESSION["NUM"], 'student' => $STUDENT));
$count = $stmt->rowCount();

#Check to see if there is really a student!
if($count == 1) {
	$row = $stmt->fetch();
	
	#Check how we are viewing the student.
	switch ($REQUEST) {
		
		#add the student to the selected class.
		case "ADDCLASS-SELECT":
			if($CLASS == "") {
				showError("Whoops!", "You didn't select a class!", "Try selecting a class first.", 400);
			}
			
			#Check ownership
			$stmt = $conn->prepare("SELECT NUM, TEACHER_NUM, NAME FROM CLASS WHERE TEACHER_NUM = :teacherNum AND NUM = :classNum");
			$stmt->execute(array('teacherNum' => $_SESSION["NUM"], 'classNum' => $CLASS));
			$classcount = $stmt->rowCount();
			if($classcount != 1) {
				showError("Whoops!", "You can't add a student to a class that doesn't belong to you!", "Try selecting another class.", 400);
			}
			
			#Get the name of the class.
			$classname = $stmt->fetch();
		
			#Check duplicates
			$stmt = $conn->prepare("SELECT STUDENT_NUM, CLASS_NUM FROM `CLASS-STUDENT_LINKER` WHERE STUDENT_NUM = :studentnum AND CLASS_NUM = :classnum");
			$stmt->execute(array('studentnum' => $STUDENT, 'classnum' => $CLASS));
			$linkcount = $stmt->rowCount();
			
			#If there is a duplicate, deny it.
			#We don't need 10,000 of the same entry
			if($linkcount > 0) {
				showError("Whoops!", "That student already belongs in that class!", "Try selecting another class or student.", 400);
			}
			
			#Use access.js to clear all things after tier 1 (so the user doesn't loose their search)
			header("JS-Redirect: removeto1");
			
			#Bind!
			$stmt = $conn->prepare("INSERT INTO `CLASS-STUDENT_LINKER` (STUDENT_NUM, CLASS_NUM) VALUES (:studentnum, :classnum)");
			$stmt->execute(array('studentnum' => $STUDENT, 'classnum' => $CLASS)); #We've already verified the number is correct.
			
			#Show that it's been bound
			showError("Ok!", "The acccount has been bound to " . htmlentities($classname["NAME"]) . ".", "We'll automatically redirect you now...", 201);
			break;
		
		
		
		
		
		
		#List classes so a teacher can add a student to a class.
		case "ADDCLASS":
			?>
			<div class="object subtitle">
				<h2>Choose the class you want to add <?php echo  htmlentities($row["FIRST_NAME"]) . " " . htmlentities($row["LAST_NAME"]); ?> to:</h2>
			</div>
			<?php
			$stmt = $conn->prepare(
<<<SQL
SELECT NUM, TEACHER_NUM, NAME, YEAR, TERM, PERIOD, DESCRIPTOR
FROM CLASS
WHERE
TEACHER_NUM = :teacherNum
ORDER BY YEAR DESC, TERM DESC, PERIOD
SQL
);
			$stmt->execute(array('teacherNum' => $_SESSION["NUM"]));	
			$classes = $stmt->fetchAll();
			listclasses("js_accounts_student_addclass_select", $classes);
			break;
		
		
		
		
		
		#Called when a client clicks on a student to remove them from a class.
		case "REMOVECLASS":
			if($CLASS == "") {
				showError("Whoops!", "You didn't select a class!", "Try selecting a class first.", 400);
			}
			
			#Check ownership
			$stmt = $conn->prepare("SELECT NUM, TEACHER_NUM, NAME FROM CLASS WHERE TEACHER_NUM = :teacherNum AND NUM = :classNum");
			$stmt->execute(array('teacherNum' => $_SESSION["NUM"], 'classNum' => $CLASS));
			$classcount = $stmt->rowCount();
			if($classcount != 1) {
				showError("Whoops!", "You can't remove a student from a class that doesn't belong to you!", "Try selecting another class.", 400);
			}
						
			#Get the name of the class.
			$classname = $stmt->fetch();
		
			#Use access.js to clear all things after tier 1 (so the user doesn't loose their search)
			header("JS-Redirect: removeto1");
			
			#Unbind!
			$stmt = $conn->prepare("DELETE FROM `CLASS-STUDENT_LINKER` WHERE STUDENT_NUM = :studentnum AND CLASS_NUM = :classnum");
			$stmt->execute(array('studentnum' => $STUDENT, 'classnum' => $CLASS)); #We've already verified the number is correct.
			
			#Show that it's been unbound
			showError("Ok!", "The acccount has been unbound from " . htmlentities($classname["NAME"]) . ".", "We'll automatically redirect you now...", 201);
			break;
		
		
		
		
		
		
		#ACTUALLY unbind the user's account!
		case "UNBIND":
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
				<p>The student...
				<ul>
				<li><b>CAN</b> be added back to this list</li>
				<li><b>CAN</b> be added to other teachers classes</li>
				<li><b>WILL</b> have access to grades from your class</li>
				<li><b>WILL</b> effect your graded criteria</li>
				<li><b>WILL</b> be able to view their rubrics</li>
				<li><b>WILL NOT</b> be deleted forever</li>
				<li><b>WILL NOT</b> appear in this list any more!</li>
				</ul>
				<p>This operation makes it easy for YOU to manage your students! Unbinding a student from your account is NOT a dangerous operation, so it's reccomended to unbind a student at the end of the term or year!
				<p>To undo these changes, navigate to Accounts > Create new account > Enter student username > Submit
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
		
			#Show some general information about the student.
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
			<a id="js_accounts_student_addclass" class="object create" href="#" data-num="<?php echo $row["NUM"]; ?>"><div class="arrow"></div>
				<h1>Add student to a class</h1>
			</a>
			<?php
			
			#Gets a list of classes that the student belongs to in relation to the currently logged in teacher.
			$stmt = $conn->prepare(
<<<SQL
SELECT CLASS.NUM, CLASS.NAME, CLASS.YEAR, CLASS.PERIOD, CLASS.TERM, CLASS.DESCRIPTOR
FROM `CLASS-STUDENT_LINKER` CSL, CLASS
WHERE
CSL.STUDENT_NUM = :studentNum AND 
CSL.CLASS_NUM = CLASS.NUM AND
CLASS.TEACHER_NUM = :teacherNum
ORDER BY YEAR DESC, TERM DESC, PERIOD
SQL
			);
			$stmt->execute(array('studentNum' => $row["NUM"], 'teacherNum' => $_SESSION['NUM']));
			$countClasses = $stmt->rowCount();

			#If there is at least one class....
			if($countClasses > 0) {
				$classes = $stmt->fetchAll();
				
				#Show a header
				?>
				<div class="object subtitle">
					<h2>Remove student from the class:</h2>
				</div>
				<?php
				
				#Print every class.
				listclasses("js_accounts_student_removeclass", $classes, "destroy");
				?>
				<div class="object subtext">
					<p>Here's what'll happen:
					<p>The student...
					<ul>
					<li><b>CAN</b> be added back to this list</li>
					<li><b>WILL</b> still effect your graded criteria</li>
					<li><b>WILL NOT</b> be able to view their projects</li>
					<li><b>WILL NOT</b> have any data lost</li>
					<li><b>WILL NOT</b> be able to access their grades from this class</li>
					</ul>
					<p>To undo these changes, add them back to this class by selecting "Add student to a class"
				</div>
				<?php
			} else {
				
				#Otherwise show a tip to add a student to a class.
				?>
				<div class="object subtitle">
					<h2>This student belongs to no classes!</h2>
				</div>
				<div class="object subtext">
					<p>Use the button above to add the student to a class.
				</div>
				<?php
			}
			
			#Output any other options
			?>
			<div class="object subtitle">
				<h2>Other options:</h2>
			</div>
			<?php

			#Do not display the ability to reset the password if the password is reset already!
			if($row["PASSWORD"] != "CHANGE") { ?>
			<a id="js_accounts_student_reset" class="object selectable" href="#" data-num="<?php echo $row["NUM"]; ?>"><div class="arrow"></div><h1>Reset password</h1></a>
			<?php 
			}

			#Finally, add the ability to unbind the account from the teacher.
			?>
			<a id="js_accounts_student_unbind" class="object selectable" href="#" data-num="<?php echo $row["NUM"]; ?>"><div class="arrow"></div><h1>Unbind account</h1></a>


			<?php
			break;
	}
	
#If we do not find the student, then the teacher was trying to access something they should not!
} else {
	showError("Whoops!", "Something went wrong when requesting that student.", "Check to see if the student still exists and is linked to your account.", 400);
}
?>