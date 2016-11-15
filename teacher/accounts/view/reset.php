<?php
#Libraries.
include "../../../restricted/headaccount.php"; ?>

<div class="object subtitle">
	<h2>Really reset <?php echo  htmlentities($student["FIRST_NAME"]) . " " . htmlentities($student["LAST_NAME"]); ?>'s password?</h2>
</div>
<div class="object subtext">
	<p>They'll be able to set a new password the next time they log in.
</div>
<a id="js_accounts_view_reset_select" class="object warn white" href="#" data-studentnum="<?php echo $student["NUM"]; ?>">
	<div class="arrow"></div>
	<h3>Yes, really reset their password</h3>
</a>
