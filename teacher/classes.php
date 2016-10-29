<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsFunction = true;
$needsSQL = true;
include "../restricted/db.php";
include "../restricted/functions.php";
include "../restricted/sql.php";
?>
<div class="object subtitle">
	<h2>Your Classes</h2>
</div>
<a id="js_classes_create" class="object create" href="#"><div class="arrow"></div><h3>Create new class</h3></a>
<?php

$classes = sql_getListOfClassesViaTeacher($_SESSION["NUM"]);
fun_listClasses("js_classes_edit", $classes);
?>