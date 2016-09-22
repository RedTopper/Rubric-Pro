<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";
$USERNAME = isset($_POST["USERNAME"]) ? $_POST["USERNAME"] : "";
$LAST_NAME = isset($_POST["LAST_NAME"]) ? $_POST["LAST_NAME"] : "";
$FIRST_NAME = isset($_POST["FIRST_NAME"]) ? $_POST["FIRST_NAME"] : "";
$NICK_NAME = isset($_POST["NICK_NAME"]) ? $_POST["NICK_NAME"] : "";
$GRADE = isset($_POST["GRADE"]) ? $_POST["GRADE"] : "";
$EXTRA = isset($_POST["EXTRA"]) ? $_POST["EXTRA"] : "";

#check to see if the user actually typed anything.
if(strlen($USERNAME) < 2) {
	showError("Error creating account!", "The username is too short.", "Please type a longer username.", 400);
}

#We need to make sure that the username doesn't already exist in BOTH tables (so we don't mistake a student for a teacher)
$stmt = $conn->prepare("SELECT USERNAME FROM TEACHER WHERE USERNAME = :username");
$stmt->execute(array('username' => $USERNAME));
$row = $stmt->fetchAll();

#Check teachers
if($stmt->rowCount() > 0) {
	showError("Error creating account!", "The username cannot be the username of another teacher!", "Please change the username field to something else.", 400);
}

#Check students
$stmt = $conn->prepare("SELECT NUM, USERNAME FROM STUDENT WHERE USERNAME = :username");
$stmt->execute(array('username' => $USERNAME));
$row = $stmt->fetch();
$studentNum = $row["NUM"];
	
#Ok, so unfortunately we have a match, we figure out how to deal with it.
if($stmt->rowCount() > 0) {
	
	#Check if there is already a link between the teacher and the student
	$stmt = $conn->prepare("SELECT STUDENT_NUM, TEACHER_NUM FROM TEACHES WHERE STUDENT_NUM = :student AND TEACHER_NUM = :teacher");
	$stmt->execute(array('student' => $studentNum, 'teacher' => $_SESSION["NUM"]));
	$row = $stmt->fetch();
	if($stmt->rowCount() > 0) {
		
		#If there is, deny the link.
		showError("Error creating account!", "The username cannot be the username of another student that's already in your class!", "Please change the username field to something else.", 400);
	} else {
		#Otherwise we need to ask the user
		$stmt = $conn->prepare(
<<<SQL
SELECT
USERNAME, PASSWORD, FIRST_NAME, LAST_NAME, NICK_NAME, GRADE, EXTRA
FROM STUDENT
WHERE NUM = :student
SQL
		);
		$stmt->execute(array('student' => $studentNum));
		$row = $stmt->fetch();
		?>
		<div class="object subtitle">
			<h2>We found a matching username!</h2>
			<h2>Is <?php echo htmlentities($row["LAST_NAME"]) . ", " . htmlentities($row["FIRST_NAME"]); ?> the student you are looking for?</h2>
		</div>

		<div class="object subtext">
			<p>Username: <?php echo htmlentities($row["USERNAME"]); ?>.
			<p>Password: <?php echo ($row["PASSWORD"] == "CHANGE" ? "No password set" : "Yes");  ?>.
			<p>Nick Name: <?php if($row["NICK_NAME"] != "") {echo htmlentities($row["NICK_NAME"]);} else {echo "None given";} ?>.
			<p>Grade level: <?php echo $row["GRADE"]; ?>.
			<p>Extra information: <?php if($row["EXTRA"] != "") {echo htmlentities($row["EXTRA"]);} else {echo "None given";} ?>.
		</div>

		<div class="object subtitle">
			<h2>Notice</h2>
		</div>
		<div class="object subtext">
			<p>If this is <b>NOT</b> the student you are looking for, edit the fields to the left, then submit the request again.
		</div>

		<a id="js_accounts_create_submit_bind" class="object create" href="#" 
		data-num="<?php echo $studentNum; ?>" data-username="<?php echo htmlentities($row["USERNAME"]); ?>">
			<div class="arrow"></div>
			<h1>Bind Accounts</h1>
		</a>
		<?php
		die();
	}
} 

if(strlen($LAST_NAME) < 2 || strlen($FIRST_NAME) < 2) {
	showError("Error creating account!", "The name is too short.", "Please type a longer first name or last name.", 400);
}

if(strlen($GRADE) < 1 || !is_numeric($GRADE)) {
	showError("Error creating account!", "The grade a student is in must be a number.", "Check what you typed, then try again.", 400);
}

#Ok, so far so good. Now we need to insert the new account.
$stmt = $conn->prepare(
<<<SQL
INSERT INTO STUDENT 
(USERNAME, PASSWORD, FIRST_NAME, LAST_NAME, NICK_NAME, GRADE, EXTRA, SETTINGS)
VALUES
(:username, :password, :first, :last, :nick, :grade, :extra, :settings)
SQL
);
$stmt->execute(array('username' => $USERNAME, 
					 'password' => "CHANGE", 
					 'first' => $FIRST_NAME, 
					 'last' => $LAST_NAME, 
					 'nick' => $NICK_NAME, 
					 'grade' => $GRADE, 
					 'extra' => $EXTRA, 
					 'settings' => "{}"));
$insertedStudent = $conn->lastInsertId();

#Ok, so we have the new account, so we need to insert it into the  database
$stmt = $conn->prepare(
<<<SQL
INSERT INTO TEACHES
(TEACHER_NUM, STUDENT_NUM)
VALUES
(:teacher, :student)
SQL
);
$stmt->execute(array('teacher' => $_SESSION['NUM'], 
					 'student' => $insertedStudent));

#Redirect using some Javascript Hackery(tm)
header("JS-Redirect: account");

#It's not really an error, but it does the same thing.
showError("Ok!", "The acccount has been created.", "We'll automatically redirect you now...", 201);
?>