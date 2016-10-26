<?php  die();
		
			#First, check if the cell exists.
			$stmt = $conn->prepare("SELECT RUBRIC_QUALITY_NUM FROM RUBRIC_CELL WHERE RUBRIC_CRITERIA_NUM = :criteria AND RUBRIC_QUALITY_NUM = :quality");
			$stmt->execute(array('criteria' => $RUBRIC_CRITERIA_NUM, 'quality' => $RUBRIC_QUALITY_NUM));
			$countcells = $stmt->rowCount();
			if($countcells != 1) {
				showError("Whoops!","I could not find the cell you are editing inside my database","Try refreshing the page to fix the problem.",400);
			}
			$cell = $stmt->fetch();
			
			#Then, check to see if the parent of the cell belongs to the rubric that we are editing.
			$stmt = $conn->prepare("SELECT RUBRIC_NUM FROM RUBRIC_QUALITY WHERE NUM = :cellparent");
			$stmt->execute(array('cellparent' => $cell["RUBRIC_QUALITY_NUM"]));
			$parent = $stmt->fetch();
			if($parent["RUBRIC_NUM"] !== $rubric["NUM"]) {
				showError("Whoops!","You cannot edit cells that do not belong to your account.","Try refreshing the page to fix the problem.",400);
			}
			
			#Update the cell
			$stmt = $conn->prepare("UPDATE RUBRIC_CELL SET CONTENTS = :contents WHERE RUBRIC_CRITERIA_NUM = :criteria AND RUBRIC_QUALITY_NUM = :quality");
			$stmt->execute(array('contents' => $CONTENTS, 'criteria' => $RUBRIC_CRITERIA_NUM, 'quality' => $RUBRIC_QUALITY_NUM));
			showError("Cell Updated!","Hey, you found an easter egg!","While you're looking at the debug tools, try not to die from looking at my code!",200);
