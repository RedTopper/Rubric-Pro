<?php
if(!isset($needsFunction)) die();

/**
 * This function lists all of the classes in a formatted list.
 *
 * $classname: The HTML class name for each button (used for JQuery binding in access.js)
 * $classes: 2D array output from the database
 * $type: The css type of the list. Curently available: "selectable" - generic grey "destroy" - Appears red "create" - Appears green. 
 *		 "selectable" by default
 */
function fun_listClasses($classname, $classes, $type = "selectable", $prependText = "", $assignmentNum = null) {
	foreach($classes as $row) { ?>
		<a class="<?php echo $classname; ?> object <?php echo $type; ?>" href="#" data-classnum="<?php echo $row["NUM"] ?>"<?php echo ($assignmentNum === null ? "" : " data-assignmentnum='$assignmentNum'");?>>
			<div class="arrow"></div>
			<h3><?php 

			#Outputs the classes
			echo $prependText . htmlentities($row["NAME"]) . "<br><div class='monospace'>" .
			"Year " . $row["YEAR"] . "<br>" .
			"Term " . $row["TERM"] . "<br>" . 
			htmlentities($row["PERIOD"]) . 
			($row["DESCRIPTOR"] !== "" ? " <br>(" . htmlentities($row["DESCRIPTOR"]) . ")</div> " : " "); ?>
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
function fun_listStudents($classname, $students) {
	foreach($students as $row) {  ?>
		<a class="<?php echo $classname;?> object selectable" href="#" data-studentnum="<?php echo $row["NUM"] ?>">
			<div class='arrow'></div>
			<h3><?php 
				#Student information
				echo 
				htmlentities($row["LAST_NAME"]) . ", " . 
				htmlentities($row["FIRST_NAME"]) . 
				htmlentities(($row["NICK_NAME"] !== "" ? " (" . $row["NICK_NAME"] . ") " : " ")); ?> 
			</h3>
			<div class='monospace'><?php
				echo "[" . htmlentities($row["USERNAME"]) . "]";
			?></div>
		</a><?php
	}
}

/**
 * This function creates a formatted list of all of the rubrics.
 *
 * $classname The HTML class name for each button (used for JQuery binding in access.js)
 * $rubrics 2D array output from the database (you must call the database yourself!)
 */
function fun_listRubrics($classname, $rubrics, $type = "selectable") {
	foreach($rubrics as $row) {  ?>
		<a class="<?php echo $classname;?> object <?php echo $type; ?>" href="#" data-rubricnum="<?php echo $row["NUM"] ?>">
			<div class='arrow'></div>
			<h3><?php 
				#rubric information
				echo htmlentities($row["SUBTITLE"]) . "<br><div class='monospace'>" . 
				$row["MAX_POINTS_PER_CRITERIA"] . " points per criteria, <br>" . 
				$row["TOTAL_POINTS"] . " points possible.</div>"; ?> 
			</h3>
		</a><?php
	}
}

/**
 * This function creates a formatted list of all of the assignments.
 *
 * $classname: The HTML class name for each button (used for JQuery binding in access.js)
 * $assignments: 2D array output from the database (you must call the database yourself!)
 */
function fun_listAssignments($classname, $assignments, $type = "selectable", $prependText = "", $rubricNumber = null, $outputDueDate = false) {
	foreach($assignments as $row) { ?>
		<div class="extraoptions">
			<a class="mainoption <?php echo $classname;?> object <?php echo $type; ?>" href="#" data-assignmentnum="<?php echo $row["NUM"] ?>"<?php echo ($rubricNumber === null ? "" : " data-rubricnum='$rubricNumber'");?>><div class='arrow'></div>
				<h3><?php echo $prependText . htmlentities($row["TITLE"]); ?></h3><?php
				
				#Format the due date if needed.
				if($outputDueDate) {
					$date = explode("-", $row["DUE_DATE"]);
					echo "<div class='monospace'>" . $date[1] . "/" . $date[2] . "/" . $date[0] . "</div>";
				} ?>
			</a><?php
			if($outputDueDate) { ?>
			<a class="extraoption object create <?php echo $classname ?>_grade" href="#" data-assignmentnum="<?php echo $row["NUM"] ?>"<?php echo ($rubricNumber === null ? "" : " data-rubricnum='$rubricNumber'");?>>
				<h3>Grade</h3>
			</a><?php
			} ?>
		</div><?php
	}
}

/**
 * This function creates a formatted list of all of the qualities in a rubric.
 *
 * $classname The HTML class name for each button (used for JQuery binding in access.js)
 * $qualities 2D array output from the database (you must call the database yourself!)
 * $maxpointspercriteria Is the maximum points that a student can obtain per criteria.
 */
function fun_listQuality($classname, $qualities, $maxpointspercriteria, $type = "") {
	
	#Show a header ?>
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
 * $rubricnum The number of the rubric that we got the criteria from.
 */
function fun_listCriterion($classname, $criterion, $type = "", $rubricnum) {
	foreach($criterion as $row) {  
		
		#Begin div or anchor if type is set
		echo ($type=="" ? "<div" : "<a") . " class='$classname object white $type' href='#' data-rubricnum='" . $rubricnum . "' data-criterionnum='" . $row["NUM"] . "'>";
		
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
function fun_createExampleTableQualities() {
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
function fun_createExampleTableCriteria() {
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
 * This function takes some component number and creates a compiled symbol tree from it.
 * A compiled symbol tree is "CSASPC.I.A.1.i" for example. This method will look at the
 * symbol of the passed number, then it's parent, then the parent of it's parent, and so
 * on, until it reaches the root.
 *
 * It's possible that this method could strain the database. I honestly have no idea.
 *
 * returns: A two dementional array where the first dimension is a list of steps it took
 *			to obtain the parent component and the second dimension is an array with the
 *			keys "TREE" and "NUM". "TREE" represents the symbol tree of the "NUM"th element.
 */
function fun_getCompiledSymbolTree($teacherNum, $num) {
	global $conn;
	$tree = array();
	
	//Main query used to select each component.
	$stmt = $conn->prepare("SELECT NUM, SYMBOL, PARENT_NUM FROM COMPONENT WHERE TEACHER_NUM = :teacher AND NUM = :num");
	
	//Run the select at least once.
	do {
		$stmt->execute(array('teacher' => $teacherNum, 'num' => $num));
		$count = $stmt->rowCount();
		
		//if it exists...
		if($count == 1) {
			$component = $stmt->fetch();
			
			//If the parent is the ROOT
			if($component["PARENT_NUM"] == null) {
				
				//Create a new sub array with indexes NUM and TREE.
				array_push($tree, array("NUM" => $component["NUM"], "TREE" => ""));
				
				//foreach tree value we need to prepend the current symbol to the other symbols we have been adding.
				foreach($tree as $key => $compile) {
					$tree[$key]["TREE"] = $component["SYMBOL"] . $compile["TREE"];
				}
				break;
				
			//Otherwise it's just a child.
			} else {
				
				//Create a new sub array with indexes NUM and TREE.
				array_push($tree, array("NUM" => $component["NUM"], "TREE" => ""));
				
				//foreach tree add the symbol to the front of it. Note. the first iteration there will be
				//one element (the array above that we pushed) so there will be one symbol in the tree spot.
				//Next loop we add the symbol to this one and the next one. 
				
				//Help for future me:
				//Iteration 1: 0: TREE: .a
				
				//Iteration 2: 0: TREE: .i.a
				//			   1: TREE: .i
				
				//Iteration 3: 0: TREE: .1.i.a
				//			   1: TREE: .1.i
				//			   2: TREE: .1
				//etc.
				foreach($tree as $key => $compile) {
					$tree[$key]["TREE"] = "." . $component["SYMBOL"] . $compile["TREE"];
				}
				$num = $component["PARENT_NUM"];
			}
		} else {
			
			//Something happened where the teacher does not have the rights to that component.
			db_showError("Whoops!", "That component doesn't belong to you.", "Try refreshing the page to fix the problem.", 400);
		}
	} while(true);
	return $tree;
}

function fun_gradeRubric($rubricNum) { 
	$qualitiesCount = 0;
	$qualities = sql_getAllQualitiesInRubric($rubricNum, $qualitiesCount);
	$cells = sql_getAllRubricCells($rubricNum);
	$criteria = sql_getAllCriteriaInRubric($rubricNum); ?>
	
	<table class="rubriceditor">
		<tr>
			<th class="corner"></th><?php

			#Print out each quality at the top
			foreach($qualities as $quality) {
				echo "<th class='rubricquality' data-qualitynum='" . $quality["NUM"] . "' data-points='" . $quality["POINTS"] . "'>" . htmlspecialchars($quality["QUALITY_TITLE"]) . "<br>" . $quality["POINTS"] . " points </th>"; 
			} ?>
			<th>Leave Comment</td>
		</tr><?php
	
		$intcol = 0;
		$introw = 0;
		foreach($cells as $cell) {
			if($intcol == 0) {
				
				#Fetch all of the linked components in the criteria.
				$trees = sql_getAllCompiledSymbolTreesFromCriteria($criteria[$introw]["NUM"]);
				
				#Begin a new column every 0.
				echo "<tr id='criterianum" . $criteria[$introw]["NUM"] . "' data-criterianum='" . $criteria[$introw]["NUM"] . "'>";
				
				#Output the criteria for the row and the components.
				echo "<th class='rubricside'>"; 
				echo $criteria[$introw]["CRITERIA_TITLE"];
				foreach($trees as $tree) {
					echo "<div class='rubriccriteria' title='" . htmlentities($tree["DESCRIPTION"]) . "'>" . htmlentities($tree["COMPILED_SYMBOL_TREE"]) . "</div>";
				}
				echo "</th>";
				
				#Go to the next row.
				$introw++;
			}
			
			#Output all of the data.
			echo "<td class='rubriccell' data-qualitynum='" . $cell["RUBRIC_QUALITY_NUM"] . "' data-criterianum='" . $cell["RUBRIC_CRITERIA_NUM"] . "'>" . htmlentities($cell["CONTENTS"]) . "</td>";
			
			#When we hit the end, finish the row and reset the column.
			if($intcol == $qualitiesCount - 1) { ?>
				<td><a  href="#" class="js_classes_edit_students_grade_comment comment" data-criterianum="<?php echo $cell["RUBRIC_CRITERIA_NUM"]; ?>">Comment</a></td><?php
				echo "</tr>";
				$intcol = -1;
			}
			
			#Go to the next column.
			$intcol++;
		} ?>
	</table> <?php
}
?>