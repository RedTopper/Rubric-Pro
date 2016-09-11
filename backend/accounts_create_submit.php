<?php
$needsAuthentication = true;
$needsAJAX = true;
include "db.php";

$USERNAME = isset($_POST["USERNAME"]) ? $_POST["USERNAME"] : "";
$LAST_NAME = isset($_POST["LAST_NAME"]) ? $_POST["LAST_NAME"] : "";
$FIRST_NAME = isset($_POST["FIRST_NAME"]) ? $_POST["FIRST_NAME"] : "";
$NICK_NAME = isset($_POST["NICK_NAME"]) ? $_POST["NICK_NAME"] : "";
$EXTRA = isset($_POST["EXTRA"]) ? $_POST["EXTRA"] : "";
?>
<div class="object subtitle"><h2>Something happened</h2></div>
<div class="object subtext">
	<p>Something happened
</div>