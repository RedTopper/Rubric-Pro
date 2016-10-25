<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "../restricted/db.php";

#Obtain global functions
$needsFunction = true;
include "../restricted/functions.php";

#Include SQL functions
$needsSQL = true;
include "../restricted/sql.php";
?>
<div class="object subtitle">
	<h2>Your classes</h2>
</div>
<a id="js_classes_create" class="object create" href="#"><div class="arrow"></div><h3>Create new class</h3></a>
<?php

$classes = sql_getListOfClassesViaTeacher($_SESSION["NUM"]);
listclasses("js_classes_edit", $classes);
?>