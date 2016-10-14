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
function listQuality($classname, $qualities, $maxpointspercriteria, $type = "") {
	
	#Show a header
	?>
	<div class="objectborder">
		<div class="inlinesmall left subtext">
			<div class="pad">Points</div>
		</div><div class="inlinelarge subtext">
			<div class="pad">Name</div>
		</div>
	</div>
	<?php
	
	#Run through all of the entries and output them.
	foreach($qualities as $row) {
	
		#Parse some of the parameters to deturmine how to encapsilate the data.
		echo ($type=="" ? "<div" : "<a") . " class='$classname objectborder $type' href='#' data-qualitynum='" . $row["NUM"] . "'>"; 
			
			#Output the HTML body. ?>
			<div class="inlinesmall left">
				<div class="pad">
					<div class='larger'>
						<?php echo $row["POINTS"]; ?>
					</div><div class='smaller'>
						/<?php echo $maxpointspercriteria; ?>
					</div>
				</div>
			</div><div class="inlinelarge">
				<div class="pad">
					<?php echo htmlentities($row["QUALITY_TITLE"]); ?>
					<?php echo ($type=="" ? "" : "<div class='arrow'></div>");?>
				</div>
			</div>
		<?php echo ($type=="" ? "</div>" : "</a>");
	}
}

/**
 * This function creates a formatted list of all of the criterion in a rubric.
 * Very simple.
 *
 * $classname The HTML class name for each button (used for JQuery binding in access.js)
 * $criterion 2D array output from the database (you must call the database yourself!)
 */
function listCriterion($classname, $criterion, $type = "") {
	foreach($criterion as $row) {  
		
		#Begin div or anchor if type is set
		echo ($type=="" ? "<div" : "<a") . " class='$classname object white $type' href='#' data-criterionnum='" . $row["NUM"] . "'>";
		
			#output arrow if type is set.
			echo ($type=="" ? "" : "<div class='arrow'></div>");
			
			#output contents
			echo "<h3>" . htmlentities($row["CRITERIA_TITLE"]) . "</h3>";
			
		#End type.
		echo ($type==""?"</div>":"</a>");
	}
}

/**
 * Function that prints a "rubric like" table to give an example
 * to a user of the section they are editing. For example, with 
 * a Qualities table, they'll see the top row of the example rubric
 * hilighted. 
 *
 * When they are editing the qualities section, they'll 
 * then see a hilighted example of the section they are editing.
 */
function createExampleTableQualities() {
?>
<div class="object subtext">
	<p>In a normal rubric, this section represents the cells colored in blue, as pictured below:</p>
</div>
<div class="padbox">
	<table class="example">
		<tr>
			<td class="dark"></td>
			<td class="selectedexample">👎</td>
			<td class="selectedexample">👍</td>
			<td class="selectedexample">💯</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</table>
</div>
<?php
}

/**
 * Another function that creates an example table. See createExampleTableQualities()
 */
function createExampleTableCriteria() {
?>
<div class="object subtext">
	<p>In a normal rubric, this section represents the cells colored in blue, as pictured below:</p>
</div>
<div class="padbox">
	<table class="example">
		<tr>
			<td class="dark"></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td class="selectedexample">📗</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td class="selectedexample">📘</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td class="selectedexample">📙</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</table>
</div>
<?php
}

/**
 * Another function that creates an example table. See createExampleTableQualities()
 */
function createExampleTableCells() {
	
}
?>