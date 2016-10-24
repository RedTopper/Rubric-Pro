<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "../../restricted/db.php";

#There isn't much here other than a web form.
?>
<div class="editor">
	<label for="username">Username: </label>
	<input id="username" type="text" name="USERNAME" placeholder="0019247"><br>
	<label for="last">Last Name: </label>
	<input id="last" type="text" name="LAST_NAME" placeholder="Snow"><br>
	<label for="first">First Name: </label>
	<input id="first" type="text" name="FIRST_NAME" placeholder="Jon"><br>
	<label for="nick">Nickname: </label>
	<input id="nick" type="text" name="NICK_NAME" placeholder="Lord Snow"><br>
	<label for="grade">Grade: </label>
	<input id="grade" type="text" name="GRADE" placeholder="12"><br>
	<label for="comment">Comment: </label>
	<input id="comment" type="text" name="EXTRA" placeholder="True King of the North"><br>
</div>
<a id="js_accounts_create_submit" class="object create" href="#"><div class="arrow"></div><h3>Submit</h3></a>
<div class="object subtitle"><h2>Notice</h2></div>
<div class="object subtext">
	<p>Students will be able to create their passwords the first time they log in.
	<p>Pro tip: Other teachers in your district might have already created an account for your student! Before typing in all the fields, try submitting the username field only, and we'll search the district for a matching account. If we find one, we'll let you know!
</div>