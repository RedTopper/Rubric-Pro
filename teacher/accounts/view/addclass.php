<?php
#Libraries.
$needsFunction = true;
include "../../../restricted/headaccount.php";
include "../../../restricted/functions.php"; ?>

<div class="object subtitle">
	<h2>Choose the class you want to add <?php echo  htmlentities($student["FIRST_NAME"]) . " " . htmlentities($student["LAST_NAME"]); ?> to:</h2>
</div>

<?php
$classes = sql_getAllClasses($_SESSION["NUM"]);
if($classes === null) { ?>
	<div class="object subtext">
		<p>Looks like you don't have any classes yet.<br>Try creating one in the sidebar!
	</div><?php
} else {
	fun_listClasses("js_accounts_view_addclass_select", $classes);
}
