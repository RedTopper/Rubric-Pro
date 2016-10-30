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
	$rubrics = sql_getAllRubrics($_SESSION["NUM"]);
} else {
	$rubrics = sql_getAllRubricsBasedOnSearch($_SESSION["NUM"], $SEARCH);
}
?>
<div class="editor">
	<input id="js_rubrics_search_box" class="full" type="text" name="SEARCH" placeholder="Filter">
</div>
<a id="js_rubrics_search" class="object query" href="#"><h3>Filter by Name</h3></a><?php

#If we are searching, tell the user what we searched, otherwise just say "Everything"
if($SEARCH !== "") { ?>
<div class="object subtitle">
	<h2><?php echo "Filter: " . htmlentities($SEARCH); ?></h2>
</div><?php 
} else { ?>
<div class="object subtitle">
	<h2>All Rubrics:</h2>
</div><?php 
}?>

<a id="js_rubrics_create" class="object create" href="#"><div class="arrow"></div><h3>Create new rubric</h3></a><?php

if($rubrics === null) { ?>
<div class="object subtext">
	<p>Looks like you don't have any rubrics yet.<br>Try creating one with the button above!</p>
</div><?php
} else {
	fun_listRubrics("js_rubrics_view", $rubrics);
}