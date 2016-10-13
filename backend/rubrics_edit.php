<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";

$needsFunction = true;
include "functions.php";
$NUM = isset($_POST["NUM"]) ? $_POST["NUM"] : null;
$REQUEST = isset($_POST["REQUEST"]) ? $_POST["REQUEST"] : "";

#Makes sure the teacher owns the rubric we are editing.
$stmt = $conn->prepare(
<<<SQL
SELECT NUM, TEACHER_NUM, MAX_POINTS_PER_CRITERIA, SUBTITLE
FROM RUBRIC
WHERE
TEACHER_NUM = :teacherID AND
NUM = :num
SQL
);
$stmt->execute(array('teacherID' => $_SESSION["NUM"], 
					 'num' => $NUM));	
$countRubrics = $stmt->rowCount();

#Check count.
if($countRubrics == 1) {
	$row = $stmt->fetch();
	
	#Check how we are viewing the edit.
	switch ($REQUEST) {
		case "BUILD": 
		
			#We need the qualities so we can populate the top of the rubric.
			#Make sure to sort by the points!
			$stmt = $conn->prepare("SELECT POINTS, QUALITY_TITLE FROM RUBRIC_QUALITY WHERE RUBRIC_NUM = :rubric ORDER BY RUBRIC_QUALITY.POINTS");
			$stmt->execute(array('rubric' => $row["NUM"]));
			$qualities = $stmt->fetchAll();
			$countQualities = $stmt->rowCount();
			
			#Next, to complete our masterpeice, we need the criteria of each row.
			$stmt = $conn->prepare("SELECT CRITERIA_TITLE FROM RUBRIC_CRITERIA WHERE RUBRIC_NUM = :rubric");
			$stmt->execute(array('rubric' => $row["NUM"]));
			$criteria = $stmt->fetchAll();
			$countCriteria = $stmt->rowCount();
			
			#check to make sure that contents actually exist within the table
			if($countQualities == 0 || $countCriteria == 0) {
				showError("Whoops!", "You'll need at least one quality and criterium before you can edit anything.", "It's easy, try adding a few to the left!", 400);
			}
				
			#Ok, now get the actual cell data
			$stmt = $conn->prepare(
<<<SQL
SELECT RUBRIC_CRITERIA_NUM, RUBRIC_QUALITY_NUM, CONTENTS
FROM RUBRIC_CELL, RUBRIC_CRITERIA, RUBRIC_QUALITY
WHERE
RUBRIC_CRITERIA.RUBRIC_NUM = :rubric AND
RUBRIC_QUALITY.RUBRIC_NUM = :rubric AND
RUBRIC_CELL.RUBRIC_QUALITY_NUM = RUBRIC_QUALITY.NUM AND
RUBRIC_CELL.RUBRIC_CRITERIA_NUM = RUBRIC_CRITERIA.NUM
ORDER BY RUBRIC_CRITERIA_NUM, RUBRIC_QUALITY.POINTS
SQL
			);
			$stmt->execute(array('rubric' => $row["NUM"]));
			$cells = $stmt->fetchAll();
			
			#Tell access.js that we need to be able to make this col huuuuge.
			header("JS-Resize: auto");
			
			#Now, output the table for the rubric. We'll start with a fancy header. ?>
			<div class="object subtitle">
				<h2>Rubric Editor</h2>
			</div>
			<table class="rubric">
				<tr>
					<th class="corner"></th>
					<?php
					foreach($qualities as $quality) {
						echo 
						"<th>" . 
							$quality["QUALITY_TITLE"] . "<br>" . $quality["POINTS"] . " points" .
						"</th>";
					}
					?>
				</tr>
				<?php
				
				$col = 0;
				$row = 0;
				foreach($cells as $cell) {
					if($col == 0) {
						#Begin a new col every 0.
						echo "<tr>";
						
						#Output the criteria for the row.
						echo 
						"<th>" . 
							$criteria[$row]["CRITERIA_TITLE"] . 
						"</th>";
						
						#Go to the next row.
						$row++;
					}
					
					#Output all of the data.
					echo "<td><textarea rows='8' cols='18' class='rubricbox'>" . $cell["CONTENTS"] . "</textarea></td>";
					
					#When we hit the end, finish the row and reset the col.
					if($col == $countQualities - 1) {
						echo "</tr>";
						$col = -1;
					}
					
					#Go to the next col.
					$col++;
				}
				?>
			</table>
			<?php
			#echo '<pre style="color: white">';
			#var_dump($cells);
			#echo '</pre>';
			break;
			
			
			
			
			
			
		#List all of the quality in a rubric.
		case "ADDQUALITY": 
			
			#First, display the user input box so the user can actually create a new quality. ?>
			<div class="object subtitle">
				<h2>Create a new quality</h2>
			</div>
			<div class="editor">
				<label for="qualityname">Quality name: </label>
				<input id="qualityname" type="text" name="QUALITY_TITLE" placeholder="Proficient"><br>
				<label for="qualitypoints">Points out of <?php echo $row["MAX_POINTS_PER_CRITERIA"] ?>: </label>
				<input id="qualitypoints" type="number" name="QUALITY_POINTS" placeholder="<?php echo $row["MAX_POINTS_PER_CRITERIA"] ?>"><br>
			</div>
			<a id="js_rubrics_edit_qualitysubmit" class="object create white" href="#" data-num="<?php echo $row["NUM"] ?>"><div class="arrow"></div><h3>Submit</h3></a><?php

			#Quick table to show the user what they are editing.
			createExampleTableQualities(); 
			
			#Then, output the qualities that are already in the table. ?>
			<div class="object subtitle">
				<h2>Your Qualities</h2>
			</div>
			<?php
			
			#Get qualities from database that match the rubric.
			$stmt = $conn->prepare(
<<<SQL
SELECT NUM, POINTS, QUALITY_TITLE
FROM RUBRIC_QUALITY
WHERE
RUBRIC_NUM = :rubric
ORDER BY POINTS
SQL
			);
			$stmt->execute(array('rubric' => $row["NUM"]));	
			$countqualities = $stmt->rowCount();
			
			#Output them if they exist, otherwise show that nothing exists. 
			if($countqualities > 0) {
				$data = $stmt->fetchAll();
				listQuality("test", $data, $row["MAX_POINTS_PER_CRITERIA"]);
			} else {
				?><div class="object subtext"><p>There's nothing here.</p></div><?php
			}
			break;
			
			
			
			
			
			
		#List all of the criteria in a rubric.
		case "ADDCRITERIA": 
		
			#output creation zone?>
			<div class="object subtitle">
				<h2>Create a new criteria</h2>
			</div>
			<div class="editor">
				<label for="criterianame">Criteria name: </label>
				<input id="criterianame" type="text" name="CRITERIA_TITLE" placeholder="Spelling and Accuracy"><br>
			</div>
			<a id="js_rubrics_edit_criteriasubmit" class="object create white" href="#" data-num="<?php echo $row["NUM"] ?>"><div class="arrow"></div><h3>Submit</h3></a><?php 
			
			#Quick table to show the user what they are editing
			createExampleTableCriteria(); 
			
			#Show contents of the rubric. ?>
			<div class="object subtitle">
				<h2>Your Criteria</h2>
			</div>
			<?php
			$stmt = $conn->prepare(
<<<SQL
SELECT NUM, CRITERIA_TITLE
FROM RUBRIC_CRITERIA
WHERE
RUBRIC_NUM = :rubric
SQL
			);
			$stmt->execute(array('rubric' => $row["NUM"]));	
			$countcriteria = $stmt->rowCount();
			if($countcriteria > 0) {
				$data = $stmt->fetchAll();
				listCriterion("test", $data);
			} else {
				?><div class="object subtext"><p>There's nothing here.</p></div><?php
			}
			break;
		
		
		
		
		
		
		#Default case, we want to see the edit options that we can do per rubric.
		case "VIEW":
		default: ?>
			<div class="object subtitle">
				<h2><?php echo htmlentities($row["SUBTITLE"])?> </h2>
			</div>
			<a id="js_rubrics_edit_editrubric" class="object selectable white" href="#" data-num="<?php echo $row["NUM"] ?>"><div class="arrow"></div><h3>Edit this rubric</h3></a>
			<a id="js_rubrics_edit_addquality" class="object create white" href="#" data-num="<?php echo $row["NUM"] ?>"><div class="arrow"></div><h3>Create or view qualities</h3></a>
			<a id="js_rubrics_edit_addcriteria" class="object create white" href="#" data-num="<?php echo $row["NUM"] ?>"><div class="arrow"></div><h3>Create or view criteria</h3></a>
			<a id="js_rubrics_edit_destroyquality" class="object warn white" href="#" data-num="<?php echo $row["NUM"] ?>"><div class="arrow"></div><h3>Choose and destroy qualities</h3></a>
			<a id="js_rubrics_edit_destroycriteria" class="object warn create" href="#" data-num="<?php echo $row["NUM"] ?>"><div class="arrow"></div><h3>Choose and destroy criteria</h3></a>
			<a id="js_rubrics_edit_destroyrubric" class="object destroy" href="#" data-num="<?php echo $row["NUM"] ?>"><div class="arrow"></div><h3>Destroy this rubric</h3></a><?php
			break;
	}
} else {
	showError("Whoops!", "That rubric number doesn't belong to you.", "Try selecting another rubric or refresh the page.", 400);
}
?>