<?php
#Libraries.
$needsFunction = true;
include "../../restricted/headaccount.php";
include "../../restricted/functions.php"; ?>

<div class="object subtitle">
	<h2>Choose the class you want to add <?php echo  htmlentities($info["FIRST_NAME"]) . " " . htmlentities($info["LAST_NAME"]); ?> to:</h2>
</div>

<?php
$classes = sql_getListOfClassesViaTeacher($_SESSION["NUM"]);
fun_listClasses("js_accounts_student_addclass_select", $classes);