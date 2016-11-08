<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsFunction = true;
$needsSQL = true;
include "../../../../restricted/db.php";
include "../../../../restricted/functions.php";
include "../../../../restricted/sql.php";

$CRITERIA_NUM = isset($_POST["CRITERIA_NUM"]) ? $_POST["CRITERIA_NUM"] : null;
$COMPONENT_NUM = isset($_POST["COMPONENT_NUM"]) ? $_POST["COMPONENT_NUM"] : null;

#Validate that the component can be null or a number greater than 0
if(!($COMPONENT_NUM == null || is_numeric($COMPONENT_NUM) && $COMPONENT_NUM > 0)) {
	showError("Whoops", "I didn't quite understand the request...", "Sorry about that!", 400);
}

#List of selected components
$components = null;

if($COMPONENT_NUM === null) {
	
	#If it's null, request the root elements
	$components = sql_getAllRootComponents($_SESSION["NUM"]);
	
	#Title for the root components. ?>
	<div class="title"><h1>Select Component</h1></div>
	<div class="object subtitle">
		<h2>Your root components:</h2>
	</div>		
	<a class="js_rubrics_edit_addcriteria_destroycomponents object destroy" href="#"><div class="arrow"></div>
		<h3>Remove all components from this criteria</h3>
	</a><?php

} else {

	#Otherwise we need to fetch the elemetns that the user requested as well as it's parent.
	$parent = sql_getComponent($_SESSION["NUM"], $COMPONENT_NUM);

	#If we do not have a matching parent show an error.
	if($parent === null) { ?>
		<div class="title"><h1>Error</h1></div><?php #show title because nothing in this section has one.
		db_showError("Whoops!", "There is no matching parent.", "Sorry about that!", 400);
	}

	#Get all components from that parent
	$components = sql_getAllSubComponentsFromComponent($_SESSION["NUM"], $COMPONENT_NUM);
	
	#Title for the sub components. ?>
	<div class="title">
		<h1><?php echo htmlentities($parent["NAME"]); ?></h1>
	</div>
	<div class="object subtitle">
		<h2>Components</h2>
	</div>
	<a class="js_rubrics_view_addcriteria_component_select object create" href="#" 
			data-componentnum="<?php echo $parent["NUM"] ?>" 
			data-criterionnum="<?php echo $CRITERIA_NUM ?>">
		<div class="arrow"></div>
		<h3>Select "<?php echo htmlentities($parent["NAME"]); ?>"</h3>
	</a><?php
}

if($components === null) { ?>
	<div class="object subtext">
		<p>There's nothing here. 
	</div>
	<?php die();
}


#We need to see if the component that's being added to the list is already in the criteria 
#that we are adding so the user has some visual basis to see what's already added.
$criteriaComponents = sql_getAllCriteriaComponents($CRITERIA_NUM);

#Display all components from the data array.
foreach($components as $viewComponent) { 

	#need to deturmine if the component already exists in the criteria.
	$exists = "NO";
	
	#Obtain all parents of the component we are viewing.
	$parentsOfView = fun_getCompiledSymbolTree($_SESSION["NUM"], $viewComponent["NUM"]);
	
	#For every component that is already selected in this criteria...
	foreach($criteriaComponents as $existingCommponent) {
		
		#check to see if this component we are viewing is directly linked to the criteria. We might not even have to traverse over the tree!
		if($existingCommponent["COMPONENT_NUM"] == $viewComponent["NUM"]) {
			$exists = "MATCH";
			break;
		}
		
		#Ok, fine, it wasn't direct. Fetch the trees of the existing components...
		$parentsOfExisting = fun_getCompiledSymbolTree($_SESSION["NUM"], $existingCommponent["COMPONENT_NUM"]);
		
		#...then foreach component in the tree....
		foreach($parentsOfExisting as $parent) {
			
			#if any one parent matches, return.
			if($parent["NUM"] == $viewComponent["NUM"]) {
				$exists = "SELECT_BECAUSE_CHILD_IS_SELECTED";
				break 2;
			}
		}
		
		#...Ok, fine again. If the component we are looking at is not specifically selected, and isn't a parent of a selected child...
		#then we need  to check if the component we are looking at is the child of a selected parent!!!
		foreach($parentsOfView as $parent) {
			
			#and if one matches, return.
			if($parent["NUM"] ==  $existingCommponent["COMPONENT_NUM"]) {
				$exists = "SELECT_BECAUSE_PARENT_IS_SELECTED";
				break 2;
			}
		}
	}
		?>
	
	<a class="js_rubrics_view_addcriteria_component object selectable js_tutorial_component_rubric_selector" href="#" 
			data-componentnum="<?php echo $viewComponent["NUM"] ?>" 
			data-criterionnum="<?php echo $CRITERIA_NUM ?>">
		<div class="arrow"></div><?php
		
		#Outputs the components
		echo "<h3>(" . $viewComponent["SYMBOL"] . ") " . htmlentities($viewComponent["NAME"]) . "</h3>";
		
		#And their descriptions
		echo "<div class='monospace'>" . htmlentities($viewComponent["DESCRIPTION"]) . "</div>"; 
		
		if($exists == "MATCH") { 
			?><h2 class="selectedcomponent">This component is specifically selected.</h2><?php
		}
		
		if($exists == "SELECT_BECAUSE_CHILD_IS_SELECTED") {
			 ?><h2 class="selectedcomponent">This component is selected because<br>a deeper component is selected.</h2><?php
		}
		if($exists == "SELECT_BECAUSE_PARENT_IS_SELECTED") {
			 ?><h2 class="selectedcomponent">This component is selected because<br>a higher level component is selected.</h2><?php
		} ?>
	</a><?php 
}