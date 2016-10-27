<?php
#Libraries.
$needsFunction = true;
include "../../../restricted/functions.php";
include "../../../restricted/headrubric.php";

#We need the qualities so we can populate the top of the rubric.
#Make sure to sort by the points!
$qualitiesCount = 0;
$qualities = sql_getAllQualitiesInRubric($NUM, $qualitiesCount);

#Next, to complete our masterpeice, we need the criteria of each row.
$criteria = sql_getAllCriteriaInRubric($NUM);

#check to make sure that contents actually exist within the table
if($qualities === null || $criteria === null) {
	db_showError("Whoops!", "You'll need at least one quality and criterion before you can edit anything.", "It's easy, try adding a few to the left!", 400);
}
	
#Ok, now get the actual cell data
$cells = sql_getAllRubricCells($NUM);

#Tell access.js that we need to be able to make this col huuuuge.
header("JS-Resize: auto");

#Now, output the table for the rubric. We'll start with a fancy header. ?>
<div class="object subtitle">
	<h2>Rubric Editor</h2>
</div>
<table class="rubriceditor">
	<tr>
		<th class="corner"></th><?php
		
		#Print out each quality at the top
		foreach($qualities as $quality) {
			echo "<th>" . htmlspecialchars($quality["QUALITY_TITLE"]) . "<br>" . $quality["POINTS"] . " points </th>"; 
		} ?>
	</tr><?php
	
	$intcol = 0;
	$introw = 0;
	foreach($cells as $cell) {
		if($intcol == 0) {
			
			#Fetch all of the linked components in the criteria.
			$trees = sql_getAllCompiledSymbolTreesFromCriteria($criteria[$introw]["NUM"]);
			
			#Begin a new column every 0.
			echo "<tr>";
			
			#Output the criteria for the row and the components.
			echo "<th>"; 
			echo $criteria[$introw]["CRITERIA_TITLE"];
			foreach($trees as $tree) {
				echo "<div class='rubriccriteria'>" . $tree["COMPILED_SYMBOL_TREE"] . "</div>";
			}
			echo "</th>";
			
			#Go to the next row.
			$introw++;
		}
		
		#Output all of the data.
		echo "<td><textarea rows='8' cols='18' class='rubricbox" .
		
		#rubric number
		"' data-num='" . $NUM . 
		
		#quality number of cell
		"' data-quality='" . $cell["RUBRIC_QUALITY_NUM"] . 
		
		#criteria number of cell
		"' data-criteria='" . $cell["RUBRIC_CRITERIA_NUM"] . 
		
		#actual contents of cell
		"'>" . htmlentities($cell["CONTENTS"]) . "</textarea></td>";
		
		#When we hit the end, finish the row and reset the column.
		if($intcol == $qualitiesCount - 1) {
			echo "</tr>";
			$intcol = -1;
		}
		
		#Go to the next column.
		$intcol++;
	} ?>
</table>
<div class="padbox" >
	<textarea placeholder="This is a temporary box that you can use when you need to move text to other boxes. It won't be saved." style="width: 100%; resize: none; box-sizing: border-box;" rows="10"></textarea>
</div>