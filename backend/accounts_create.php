<?php
$needsAuthentication = true;
$needsAJAX = true;
include "db.php";

switch ($_SESSION["TYPE"]) {
	case 0:
		showError("Not Allowed", "Students may not create other student accounts.", "How did you even request this?", 403);
		break;
	case 1:
		break;
	case 2:
		break;
}
?>