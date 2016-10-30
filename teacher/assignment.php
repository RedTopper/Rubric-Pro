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

$SEARCH = isset($_POST["SEARCH"]) ? $_POST["SEARCH"] : "";

#Sanatize $SEARCH (will be included in PDO, so no sql injection). Removes extra wild cards.
$SEARCH = preg_replace('/%+/', '', $SEARCH); 

if($SEARCH === "") {
	$assignments = sql_getAllAssignments($_SESSION["NUM"]);
} else {
	$assignments = sql_getAllAssignmentsBasedOnSearch($_SESSION["NUM"], $SEARCH);
}
?>
<div class="editor">
	<input id="js_assignments_search_box" class="full" type="text" name="SEARCH" placeholder="Filter">
</div>
<a id="js_assignments_search" class="object query" href="#"><h3>Filter by Name</h3></a>
<div class="object spacer"></div><?php

#If we are searching, tell the user what we searched, otherwise just say "Everything"
if($SEARCH !== "") { ?>
	<div class="object subtitle">
		<h2><?php echo "Filter: " . htmlentities($SEARCH); ?></h2>
	</div><?php 
} else { ?>
	<div class="object subtitle">
		<h2>All Assignments:</h2>
	</div><?php 
}?>
<a id="js_assignment_create" class="object create" href="#">
	<div class="arrow">
	</div><h3>Create new assignment</h3>
</a>
<?php

if($assignments === null) { ?>
	<div class="object subtext">
		<p>Looks like you don't have any assignments yet.
		<p>Try creating one with the button above!
	</div><?php
} else {
	fun_listAssignments("js_assignments_view", $assignments);
}