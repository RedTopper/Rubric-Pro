<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";

$needsFunction = true;
include "functions.php";
$COMPONENT_NUM = isset($_POST["COMPONENT_NUM"]) ? $_POST["COMPONENT_NUM"] : null;
$RUBRIC_NUM = isset($_POST["RUBRIC_NUM"]) ? $_POST["RUBRIC_NUM"] : null;
$CRITERIA_NUM = isset($_POST["CRITERIA_NUM"]) ? $_POST["CRITERIA_NUM"] : null;

#Check if the criteria exists.
$stmt = $conn->prepare("SELECT RUBRIC_NUM FROM RUBRIC_CRITERIA WHERE NUM = :criteria");
$stmt->execute(array('criteria' => $CRITERIA_NUM));
$countCriteria = $stmt->rowCount();
if($countCriteria != 1) {
	showError("Whoops!", "That criteria does not exist.", "Try selecting a different criteria and then try again.", 400);
}
$criteria = $stmt->fetch();

#Check if the the rubric of the criteria belongs to the teacher.
$stmt = $conn->prepare("SELECT TEACHER_NUM FROM RUBRIC WHERE NUM = :rubric AND TEACHER_NUM = :teacher");
$stmt->execute(array('rubric' => $criteria["RUBRIC_NUM"], "teacher" => $_SESSION["NUM"]));
$countRubric = $stmt->rowCount();
if($countRubric != 1) {
	showError("Whoops!", "That rubric does not belong to you.", "Try refreshing the page and then try again.", 400);
}

#Fetch all of the linked components in the criteria.
$stmt = $conn->prepare("SELECT COMPONENT_NUM FROM CRITERION WHERE RUBRIC_CRITERIA_NUM = :criteria");
$stmt->execute(array('criteria' => $CRITERIA_NUM));
$data = $stmt->fetchAll();

#Simple check to see if the component we are adding already exists.
foreach($data as $existingComponents) {
	if($existingComponents["COMPONENT_NUM"] == $COMPONENT_NUM) {
		showError("Whoops!", "That component already exists for that criteria.","You can continue to select more components if you want (just not that one).", 400);
	}
}

#Now it gets fun....

//Check to see if the component we are adding is a SUBSET of what's there.
//(in other words, check if the component we are adding is a parent of a child component that is already there.)
//If it is, delete that component and replace it with the LESS specific child component.

#The problem with selecting something that's more broad is that there may be many things that are more specific.
#In this case, we'll need to cycle through EVERYTHING in order to figure out what to delete.
$amountDeleted = 0;
$lastNameDeleted = "";

#For each component already in the criteria...
foreach($data as $existingComponents) {
	
	#...fetch the tree...
	$parents = getCompiledSymbolTree($_SESSION["NUM"], $existingComponents["COMPONENT_NUM"]);
	
	#...then foreach component in the tree....
	foreach($parents as $parent) {
		
		#TL DR: IF THE DATABASE HAS A CHILD COMPONENT OF THE COMPONENT WE ARE ADDING, DELETE AND REPLACE IT WITH THE COMPONENT WE ARE ADDING.
		if($parent["NUM"] == $COMPONENT_NUM) {
			
			#delete the existing component
			$stmt = $conn->prepare("DELETE FROM CRITERION WHERE RUBRIC_CRITERIA_NUM = :criteria AND COMPONENT_NUM = :component");
			$stmt->execute(array('criteria' => $CRITERIA_NUM, "component" => $parents[0]["NUM"]));
			
			$amountDeleted++;
			$lastNameDeleted = $parents[0]["TREE"];
		}
	}
}
if($amountDeleted > 0) {
	
	#Replace it with the component we are adding.
	$stmt = $conn->prepare("INSERT INTO CRITERION (RUBRIC_CRITERIA_NUM, COMPONENT_NUM, COMPILED_SYMBOL_TREE) VALUES (:criteria, :component, :tree)");
	$stmt->execute(array('criteria' => $CRITERIA_NUM, "component" => $COMPONENT_NUM, "tree" => $parent["TREE"]));
	
	#Show to user. We'll be nice and show the replaced name if it's only one thing.
	showError("Update!", "Automatically swapped " . ($amountDeleted == 1 ? "the component '" . $lastNameDeleted . "'" : $amountDeleted . " component(s)") . " for the less specific component '" . $parent["TREE"] . "'.",
	"You can continue to select more components if you want.", 200);
}

//Now we need to check to see if the component we are adding is a SUPERSET of a component we already have 
//(in other words, check if the component we are adding has a child of something already there.)
//If it is, delete that and replace it with the MORE specific component.
#Fetch the tree....
$parents = getCompiledSymbolTree($_SESSION["NUM"], $COMPONENT_NUM);

#...then foreach component in the tree of the component we are adding...
foreach($parents as $parent) {
	
	#...then foreach existing component...
	foreach($data as $existingComponents) {
		
		#TL DR: IF THE COMPONENT WE ARE ADDING HAS A CHILD COMPONENT IN THE DATABASE, DELETE IT AND REPLACE IT WITH THE COMPONENT WE ARE ADDING.
		if($existingComponents["COMPONENT_NUM"] == $parent["NUM"]) {
			
			#delete the existing component
			$stmt = $conn->prepare("DELETE FROM CRITERION WHERE RUBRIC_CRITERIA_NUM = :criteria AND COMPONENT_NUM = :component");
			$stmt->execute(array('criteria' => $CRITERIA_NUM, "component" => $existingComponents["COMPONENT_NUM"]));
			
			#replace it with the component we are adding.
			$stmt = $conn->prepare("INSERT INTO CRITERION (RUBRIC_CRITERIA_NUM, COMPONENT_NUM, COMPILED_SYMBOL_TREE) VALUES (:criteria, :component, :tree)");
			$stmt->execute(array('criteria' => $CRITERIA_NUM, "component" => $COMPONENT_NUM, "tree" => $parents[0]["TREE"]));
			
			#show to user.
			showError("Update!", "Automatically swapped the component '" . getCompiledSymbolTree($_SESSION["NUM"], $existingComponents["COMPONENT_NUM"])[0]["TREE"] . 
			"' for the more specific component '" . $parents[0]["TREE"] . "'.", "You can continue to select more components if you want.", 200);
		}
	}
}

#Worst case. Nothings there. Plain and simple add.
$stmt = $conn->prepare("INSERT INTO CRITERION (RUBRIC_CRITERIA_NUM, COMPONENT_NUM, COMPILED_SYMBOL_TREE) VALUES (:criteria, :component, :tree)");
$stmt->execute(array('criteria' => $CRITERIA_NUM, "component" => $COMPONENT_NUM, "tree" => $parents[0]["TREE"]));

showError("Success!", "The new component has been added.","You can continue to select more components if you want.", 200);