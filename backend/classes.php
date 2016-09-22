<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";

$needsFunction = true;
include "function_listclasses.php";
?>
<div class="object subtitle">
	<h2>Your classes</h2>
</div>
<a id="js_classes_create" class="object create" href="#"><div class="arrow"></div><h1>Create new class</h1></a>
<?php
listclasses("js_classes_edit");
?>