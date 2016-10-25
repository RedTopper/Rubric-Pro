<?php
#Libraries.
include "../../restricted/view_verify.php"; ?>

<div class="object subtitle">
	<h2>Really reset <?php echo  htmlentities($info["FIRST_NAME"]) . " " . htmlentities($info["LAST_NAME"]); ?>'s password?</h2>
</div>
<div class="object subtext">
	<p>They'll be able to set a new password the next time they log in.
</div>
<a id="js_accounts_student_reset_yes" class="object warn white" href="#" data-num="<?php echo $info["NUM"]; ?>">
	<div class="arrow"></div>
	<h3>Yes, really reset their password</h3>
</a>
