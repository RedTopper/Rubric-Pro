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
			<h3>
			<?php 

			#Outputs the classes
			echo htmlentities($row["NAME"]) . "<br><div class='monospace'>" .
			"Year " . $row["YEAR"] . "<br>" .
			"Term " . $row["TERM"] . "<br>" . 
			htmlentities($row["PERIOD"]) . 
			($row["DESCRIPTOR"] !== "" ? " <br>(" . htmlentities($row["DESCRIPTOR"]) . ")</div> " : " "); 
			?>
			</h3>
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
			
		<h3>
		<?php 
		
		#Student information
		echo 
		htmlentities($row["LAST_NAME"]) . ", " . 
		htmlentities($row["FIRST_NAME"]) . 
		htmlentities(($row["NICK_NAME"] !== "" ? " (" . $row["NICK_NAME"] . ") " : " ")) .
		"<br><div class='monospace'>[" . 
		htmlentities($row["USERNAME"]) . "]</div> ";	
		?> 
		</h3>
			
		<?php echo ($selectable ? "</a>" : "</div>"); 
	}
}

/**
 * This function creates a formatted list of all of the rubrics.
 *
 * $classname The HTML class name for each button (used for JQuery binding in access.js)
 * $rubrics 2D array output from the database (you must call the database yourself!)
 */
function listRubrics($classname, $rubrics) {
	foreach($rubrics as $row) {  ?>
		<a class="<?php echo $classname;?> object selectable" href="#" data-num="<?php echo $row["NUM"] ?>"><div class='arrow'></div>
			<h3>
			<?php 
			#rubric information
			echo htmlentities($row["SUBTITLE"]) . "</div><br><div class='monospace'>" . 
			$row["MAX_POINTS_PER_CRITERIA"] . " points per criteria, <br>" . 
			$row["TOTAL_POINTS"] . " points possible.</div>";
			?> 
			</h3>
		</a>
		<?php
	}
}

/**
 * This function creates a formatted list of all of the qualities in a rubric.
 *
 * $classname The HTML class name for each button (used for JQuery binding in access.js)
 * $qualities 2D array output from the database (you must call the database yourself!)
 * $maxpointspercriteria Is the maximum points that a student can obtain per criteria.
 */
function listQuality($classname, $qualities, $maxpointspercriteria) {
	?>
	<div class="objectborder">
		<div class="inlinesmall left subtext">
			<div class="pad">Points</div>
		</div><div class="inlinelarge subtext">
			<div class="pad">Name</div>
		</div>
	</div>
	<?php
	foreach($qualities as $row) {  ?>
		<a class="<?php echo $classname;?> objectborder selectable" href="#" data-num="<?php echo $row["NUM"] ?>">
			<div class="inlinesmall left">
				<div class="pad"><?php echo 
					"<div class='larger'>" . 
						$row["POINTS"] . 
					"</div><div class='smaller'>/" . 
						$maxpointspercriteria . 
					"</div>"; ?></div>
			</div><div class="inlinelarge">
				<div class="pad"><?php echo htmlentities($row["QUALITY_TITLE"]); ?><div class='arrow'></div></div>
			</div>
		</a>
		<?php
	}
}

/**
 * This function creates a formatted list of all of the criterion in a rubric.
 * Very simple.
 *
 * $classname The HTML class name for each button (used for JQuery binding in access.js)
 * $criterion 2D array output from the database (you must call the database yourself!)
 */
function listCriterion($classname, $criterion) {
	foreach($criterion as $row) {  ?>
		<a class="<?php echo $classname;?> object selectable white" href="#" data-num="<?php echo $row["NUM"] ?>"><div class='arrow'></div>
			<h3><?php echo htmlentities($row["CRITERIA_TITLE"]); ?></h3>
		</a>
		<?php
	}
}
?>