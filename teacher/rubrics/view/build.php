<?php
#Libraries.
$needsFunction = true;
include "../../../restricted/functions.php";
include "../../../restricted/headrubric.php";

#We need the qualities so we can populate the top of the rubric.
#Make sure to sort by the points!
$qualitiesCount = 0;
$qualities = sql_getAllQualitiesInRubric($RUBRIC_NUM, $qualitiesCount);

#Next, to complete our masterpeice, we need the criteria of each row.
$criteria = sql_getAllCriteriaInRubric($RUBRIC_NUM);

#check to make sure that contents actually exist within the table
if($qualities === null || $criteria === null) {
	db_showError("Whoops!", "You'll need at least one quality and criterion before you can edit anything.", "It's easy, try adding a few to the left!", 400);
}
	
#Ok, now get the actual cell data
$cells = sql_getAllRubricCells($RUBRIC_NUM);

#Tell access.js that we need to be able to make this col huuuuge.
header("JS-Resize: auto");

#Now, output the table for the rubric. We'll start with a fancy header. ?>
<div class="object subtitle">
	<h2>Rubric Editor</h2>
</div>
<div class="padbox" >
<textarea id="importer" placeholder="You can use this box to import text from Google Sheets, Microsoft Excel, Microsoft Word, or a website. Simply select all of the cells of the body of the rubric you would like to import, copy it, and then paste it into this box. WARNING: IMPORTING DATA IS A DESTRUCTIVE ACTION!" style="width: 100%; resize: none; box-sizing: border-box;" rows="6"></textarea>
</div>
<a href="#" id="js_rubrics_view_build_import" class="object destroy white">
	<div class="arrow"></div>
	<h3>Import above text</h3>
</a>
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
				echo "<div class='rubriccriteria'>" . htmlentities($tree["COMPILED_SYMBOL_TREE"]) . "</div>";
			}
			echo "</th>";
			
			#Go to the next row.
			$introw++;
		}
		
		#Output all of the data.
		echo "<td><textarea rows='8' cols='18' class='rubricbox" .
		
		#rubric number
		"' data-rubricnum='" . $RUBRIC_NUM . 
		
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