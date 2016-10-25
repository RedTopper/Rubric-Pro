<?php
include "../../restricted/view_verify.php";

#General global functions.
$needsFunction = true;
include "../../restricted/functions.php";

###################################

#Show some general information about the student. ?>
<div class="object subtitle">
	<h2>Editing: <?php echo htmlentities($info["LAST_NAME"]) . ", " . htmlentities($info["FIRST_NAME"]); ?></h2>
</div>
<div class="object subtext">
	<p>Username: <?php echo htmlentities($info["USERNAME"]); ?>.
	<p>Password: <?php echo ($info["PASSWORD"] == "CHANGE" ? "No password set" : "Yes");  ?>.
	<p>Nick Name: <?php if($info["NICK_NAME"] != "") {echo htmlentities($info["NICK_NAME"]);} else {echo "None given";} ?>.
	<p>Grade level: <?php echo $info["GRADE"]; ?>.
	<p>Extra information: <?php if($info["EXTRA"] != "") {echo htmlentities($info["EXTRA"]);} else {echo "None given";} ?>.
</div>
<a id="js_accounts_student_addclass" class="object create" href="#" data-num="<?php echo $info["NUM"]; ?>"><div class="arrow"></div>
	<h3>Bind this student to a class</h3>
</a><?php

#Gets a list of classes that the student belongs to in relation to the currently logged in teacher.
$classes = sql_getListOfStudentClassesViaTeacher($_SESSION['NUM'], $info["NUM"]);

#If there are no classes
if($classes == null) {	
	#Show a tip to add a student to a class. ?>
	
	<div class="object subtitle">
		<h2>This student belongs to no classes!</h2>
	</div>
	<div class="object subtext">
		<p>Use the button above to add the student to a class.
	</div><?php
} else {
	#Show a header ?>
	
	<div class="object subtitle">
		<h2>Unbind this student from a class:</h2>
	</div><?php
	
	#Print every class.
	listclasses("js_accounts_student_removeclass", $classes, "warn");
}

#Output any other options ?>
<div class="object subtext spacer"></div>
<div class="object subtitle">
	<h2>Other options:</h2>
</div><?php

#Do not display the ability to reset the password if the password is reset already!
if($info["PASSWORD"] != "CHANGE") { ?>
	<a id="js_accounts_student_reset" class="object warn white" href="#" data-num="<?php echo $info["NUM"]; ?>">
		<div class="arrow"></div>
		<h3>Reset password</h3>
	</a><?php 
}

#Finally, add the ability to unbind the account from the teacher. ?>
<a id="js_accounts_student_unbind" class="object warn white" href="#" data-num="<?php echo $info["NUM"]; ?>">
	<div class="arrow"></div>
	<h3>Unbind account</h3>
</a>

<div class="object subtext spacer"></div>
<div class="object subtitle">
	<h2>About unbinding students from classes</h2>
</div>
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
	<p>To undo these changes, add them back to this class by selecting "Bind this student to a class"
</div>