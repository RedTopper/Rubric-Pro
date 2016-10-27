<?php  die();
#Libraries.
$needsFunction = true;
include "../../../restricted/functions.php";
include "../../../restricted/headrubric.php";

#We need the qualities so we can populate the top of the rubric.
#Make sure to sort by the points!
$stmt = $conn->prepare("SELECT POINTS, QUALITY_TITLE FROM RUBRIC_QUALITY WHERE RUBRIC_NUM = :rubric ORDER BY RUBRIC_QUALITY.POINTS");
$stmt->execute(array('rubric' => $rubric["NUM"]));
$qualities = $stmt->fetchAll();
$countQualities = $stmt->rowCount();

#Next, to complete our masterpeice, we need the criteria of each row.
$stmt = $conn->prepare("SELECT NUM, CRITERIA_TITLE FROM RUBRIC_CRITERIA WHERE RUBRIC_NUM = :rubric");
$stmt->execute(array('rubric' => $rubric["NUM"]));
$criteria = $stmt->fetchAll();
$countCriteria = $stmt->rowCount();

#check to make sure that contents actually exist within the table
if($countQualities == 0 || $countCriteria == 0) {
	showError("Whoops!", "You'll need at least one quality and criterion before you can edit anything.", "It's easy, try adding a few to the left!", 400);
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
$stmt->execute(array('rubric' => $rubric["NUM"]));
$cells = $stmt->fetchAll();

#Tell access.js that we need to be able to make this col huuuuge.
header("JS-Resize: auto");

#Now, output the table for the rubric. We'll start with a fancy header. ?>
<div class="object subtitle">
	<h2>Rubric Editor</h2>
</div>
<table class="rubriceditor">
	<tr>
		<th class="corner"></th>
		<?php
		
		#Print out each quality at the top
		foreach($qualities as $quality) { ?>
		<th>
			<?php echo $quality["QUALITY_TITLE"] . "<br>" . $quality["POINTS"] . " points"; ?> 
		</th>
		
		<?php 
		#end
		} ?>
	</tr>
	<?php
	
	$intcol = 0;
	$introw = 0;
	foreach($cells as $cell) {
		if($intcol == 0) {
			#Fetch all of the linked components in the criteria.
			$stmt = $conn->prepare("SELECT COMPILED_SYMBOL_TREE FROM CRITERION WHERE RUBRIC_CRITERIA_NUM = :criteria");
			$stmt->execute(array('criteria' => $criteria[$introw]["NUM"]));
			$data = $stmt->fetchAll();
			
			#Begin a new column every 0.
			echo "<tr>";
			
			#Output the criteria for the row and the components.
			echo "<th>"; 
			echo $criteria[$introw]["CRITERIA_TITLE"];
			foreach($data as $component) {
				echo "<div class='rubriccriteria'>" . $component["COMPILED_SYMBOL_TREE"] . "</div>";
			}
			echo "</th>";
			
			#Go to the next row.
			$introw++;
		}
		
		#Output all of the data.
		echo "<td><textarea rows='8' cols='18' class='rubricbox" .
		
		#rubric number
		"' data-num='" . $rubric["NUM"] . 
		
		#quality number of cell
		"' data-quality='" . $cell["RUBRIC_QUALITY_NUM"] . 
		
		#criteria number of cell
		"' data-criteria='" . $cell["RUBRIC_CRITERIA_NUM"] . 
		
		#actual contents of cell
		"'>" . $cell["CONTENTS"] . "</textarea></td>";
		
		#When we hit the end, finish the row and reset the column.
		if($intcol == $countQualities - 1) {
			echo "</tr>";
			$intcol = -1;
		}
		
		#Go to the next column.
		$intcol++;
	}
	?>
</table>
<div class="padbox" >
	<textarea placeholder="This is a temporary box that you can use when you need to move text to other boxes. It won't be saved." style="width: 100%; resize: none; box-sizing: border-box;" rows="10"></textarea>
</div>
<?php
#echo '<pre style="color: white">';
#var_dump($cells);
#echo '</pre>';