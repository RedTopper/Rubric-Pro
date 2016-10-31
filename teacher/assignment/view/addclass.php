<?php
#Libraries.
$needsFunction = true;
include "../../../restricted/headassignment.php";
include "../../../restricted/functions.php"; ?>
<div class="object subtitle">
	<h2>First, choose a due date:</h2>
</div>
<div class="editor">
	<input id="js_assignments_addclass_datepick" class="full datepicker" type="text" name="DUE_DATE" placeholder="Press here to set a due date">
</div>

<div class="object subtitle">
	<h2>Then, choose a class:</h2>
</div>

<?php
$assignments = sql_getAllClasses($_SESSION["NUM"]);
fun_listClasses("js_assignments_view_addclass_select", $assignments, "selectable", "", $ASSIGNMENT_NUM);