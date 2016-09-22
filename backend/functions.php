<?php
if(!isset($needsFunction)) die();

/**
 * This function lists all of the classes in a formatted list.
 *
 * $classname The HTML class name for each button (used for JQuery binding in access.js)
 * $data 2D array output from the database
 * $type The css type of the list. Curently available: "selectable" - generic grey "destroy" - Appears red "create" - Appears green. 
 *		 "selectable" by default
 */
function listclasses($classname, $data, $type = "selectable") {
	foreach($data as $row) { ?>
		<a class="<?php echo $classname; ?> object <?php echo $type; ?>" href="#" data-num="<?php echo $row["NUM"] ?>">
		<div class="arrow"></div>
			<h1>
			<?php 

			#Outputs the classes
			echo "[" . 
			htmlentities($row["NAME"]) . "]:<br> Year " . 
			$row["YEAR"] . ", Term " . 
			$row["TERM"] . ", " . 
			htmlentities($row["PERIOD"]) . 
			($row["DESCRIPTOR"] !== "" ? " <br><div class='monospace'>(" . htmlentities($row["DESCRIPTOR"]) . ")</div> " : " "); 
			?>
			</h1>
		</a>
		<?php 
	}
}

/**
 * This function creates a formatted list of all of the students.
 *
 * $classname The HTML class name for each button (used for JQuery binding in access.js)
 * $students 2D array output from the database (you must call the database yourself!)
 * $selectable True if the objects can be selected, false otherwise. True by default.
 */
function listStudents($classname, $students, $selectable = true) {
	foreach($students as $row) {  ?>
		<?php if ($selectable) { ?>
			<a class="<?php echo $classname;?> object selectable" href="#" data-num="<?php echo $row["NUM"] ?>">
			<div class='arrow'></div>
		<?php } else { ?>
			<div class="object">
		<?php } ?>
			
			<h1>
			<?php 
			#Student information
			echo "<div class='monospace'>[" . 
			htmlentities($row["USERNAME"]) . "]:</div> " . 
			htmlentities($row["LAST_NAME"]) . ", " . 
			htmlentities($row["FIRST_NAME"]) . 
			htmlentities(($row["NICK_NAME"] !== "" ? " (" . $row["NICK_NAME"] . ") " : " ")); 
			?> 
			</h1>
			
		<?php echo ($selectable ? "</a>" : "</div>"); 
	}
}
?>