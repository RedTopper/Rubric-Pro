<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsFunction = true;
$needsSQL = true;
include "../restricted/db.php";
include "../restricted/functions.php";
include "../restricted/sql.php"; ?>

<div class="object subtitle">
	<h2>Your Classes</h2>
</div>
<a id="js_classes_create" class="object create" href="#"><div class="arrow"></div><h3>Create new class</h3></a><?php

$classes = sql_getAllClasses($_SESSION["NUM"]);
if($classes === null) { ?>
	<div class="object subtext">
		<p>Looks like you don't have any classes yet.<br>Try creating one with the button above!
	</div><?php
} else {
	fun_listClasses("js_classes_edit", $classes);
}
?>