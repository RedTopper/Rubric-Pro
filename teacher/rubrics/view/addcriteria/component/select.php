<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsFunction = true;
$needsSQL = true;
include "../../../../../restricted/db.php";
include "../../../../../restricted/functions.php";
include "../../../../../restricted/sql.php";

$CRITERIA_NUM = isset($_POST["CRITERIA_NUM"]) ? $_POST["CRITERIA_NUM"] : null;
$ADDING_COMPONENT_NUM = isset($_POST["COMPONENT_NUM"]) ? $_POST["COMPONENT_NUM"] : null;

#Check if the criteria exists.
$criterion = sql_getCriteria($CRITERIA_NUM);
if($criterion === null) {
	db_showError("Whoops!", "That criteria does not exist.", "Try selecting a different criteria and then try again.", 400);
}

#Check if the the rubric of the criteria belongs to the teacher. We do this by using the previously selected criteria's rubric number.
$rubric = sql_getRubric($_SESSION["NUM"], $criterion["RUBRIC_NUM"]);
if($rubric === null) {
	db_showError("Whoops!", "That rubric does not belong to you.", "Try refreshing the page and then try again.", 400);
}

#Fetch all of the linked components in the criteria.
$allExistingComponents = sql_getAllCriteriaComponents($CRITERIA_NUM);

#Simple check to see if the component we are adding already exists.
foreach($allExistingComponents as $existingComponent) {
	if($existingComponent["COMPONENT_NUM"] == $ADDING_COMPONENT_NUM) {
		db_showError("Whoops!", "That component already exists for that criteria.","You can continue to select more components if you want (just not that one).", 400);
	}
}

#Redirect the page
header("JS-Redirect: removeto3");

#Now it gets fun....
#Fetch the tree....
$allAddingComponentParents = fun_getCompiledSymbolTree($_SESSION["NUM"], $ADDING_COMPONENT_NUM);

//Check to see if the component we are adding is a SUBSET of what's there.
//(in other words, check if the component we are adding is a parent of a child component that is already there.)
//If it is, delete that component and replace it with the LESS specific child component.

#The problem with selecting something that's more broad is that there may be many things that are more specific.
#In this case, we'll need to cycle through EVERYTHING in order to figure out what to delete.
$amountDeleted = 0;
$lastNameDeleted = "";

#For each component already in the criteria...
foreach($allExistingComponents as $existingComponent) {
	
	#...fetch the tree...
	$allExistingComponentParents = fun_getCompiledSymbolTree($_SESSION["NUM"], $existingComponent["COMPONENT_NUM"]);
	
	#...then foreach component in the tree....
	foreach($allExistingComponentParents as $existingComponentParent) {
		
		#IF THE DATABASE HAS A CHILD COMPONENT OF THE COMPONENT WE ARE ADDING, DELETE AND REPLACE IT WITH THE COMPONENT WE ARE ADDING.
		if($existingComponentParent["NUM"] == $ADDING_COMPONENT_NUM) {
			
			#delete the existing component
			$stmt = $conn->prepare("DELETE FROM CRITERION WHERE RUBRIC_CRITERIA_NUM = :criteria AND COMPONENT_NUM = :component");
			$stmt->execute(array('criteria' => $CRITERIA_NUM, "component" => $allExistingComponentParents[0]["NUM"]));
			
			$amountDeleted++;
			$lastNameDeleted = $allExistingComponentParents[0]["TREE"];
			
			#break out of inner loop and find other matching existing components.
			break;
		}
	}
}
if($amountDeleted > 0) {
	
	#Replace it with the component we are adding.
	$stmt = $conn->prepare("INSERT INTO CRITERION (RUBRIC_CRITERIA_NUM, COMPONENT_NUM, COMPILED_SYMBOL_TREE) VALUES (:criteria, :component, :tree)");
	$stmt->execute(array('criteria' => $CRITERIA_NUM, "component" => $ADDING_COMPONENT_NUM, "tree" => $allAddingComponentParents[0]["TREE"]));
	
	#Show to user. We'll be nice and show the replaced name if it's only one thing.
	db_showError("Update!", "Automatically swapped " . ($amountDeleted == 1 ? "the component '" . $lastNameDeleted . "'" : $amountDeleted . " component(s)") . " for the less specific component '" . $allAddingComponentParents[0]["TREE"] . "'.",
	"You can continue to select more components if you want.", 200);
}

//Now we need to check to see if the component we are adding is a SUPERSET of a component we already have 
//(in other words, check if the component we are adding has a child of something already there.)
//If it is, delete that and replace it with the MORE specific component.

#foreach component in the tree of the component we are adding...
foreach($allAddingComponentParents as $addingComponentParent) {
	
	#...then foreach existing component...
	foreach($allExistingComponents as $existingComponent) {
		
		#TL DR: IF THE COMPONENT WE ARE ADDING HAS A CHILD COMPONENT IN THE DATABASE, DELETE IT AND REPLACE IT WITH THE COMPONENT WE ARE ADDING.
		if($existingComponent["COMPONENT_NUM"] == $addingComponentParent["NUM"]) {
			
			#delete the existing component
			$stmt = $conn->prepare("DELETE FROM CRITERION WHERE RUBRIC_CRITERIA_NUM = :criteria AND COMPONENT_NUM = :component");
			$stmt->execute(array('criteria' => $CRITERIA_NUM, "component" => $existingComponent["COMPONENT_NUM"]));
			
			#replace it with the component we are adding.
			$stmt = $conn->prepare("INSERT INTO CRITERION (RUBRIC_CRITERIA_NUM, COMPONENT_NUM, COMPILED_SYMBOL_TREE) VALUES (:criteria, :component, :tree)");
			$stmt->execute(array('criteria' => $CRITERIA_NUM, "component" => $ADDING_COMPONENT_NUM, "tree" => $allAddingComponentParents[0]["TREE"]));
			
			#show to user.
			db_showError("Update!", "Automatically swapped the component '" . fun_getCompiledSymbolTree($_SESSION["NUM"], $existingComponent["COMPONENT_NUM"])[0]["TREE"] . 
			"' for the more specific component '" . $allAddingComponentParents[0]["TREE"] . "'.", "You can continue to select more components if you want.", 200);
		}
	}
}

#Worst case. Nothings there. Plain and simple add.
$stmt = $conn->prepare("INSERT INTO CRITERION (RUBRIC_CRITERIA_NUM, COMPONENT_NUM, COMPILED_SYMBOL_TREE) VALUES (:criteria, :component, :tree)");
$stmt->execute(array('criteria' => $CRITERIA_NUM, "component" => $ADDING_COMPONENT_NUM, "tree" => $allAddingComponentParents[0]["TREE"]));

db_showError("Ok!", "The new component has been added.","You can continue to select more components if you want.", 200);