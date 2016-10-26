<?php
#Libraries.
include "../../restricted/headaccount.php"; ?>

<div class="object subtitle">
	<h2>Really unbind <?php echo  htmlentities($info["FIRST_NAME"]) . " " . htmlentities($info["LAST_NAME"]); ?> from your account?</h2>
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
<a id="js_accounts_student_unbind_yes" class="object warn white" href="#" data-num="<?php echo $info["NUM"]; ?>">
	<div class="arrow"></div>
	<h3>Yes, unbind <?php echo  htmlentities($info["FIRST_NAME"]) . " " . htmlentities($info["LAST_NAME"]); ?></h3>
</a>