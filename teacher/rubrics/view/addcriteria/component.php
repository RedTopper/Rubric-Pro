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
	<a class="js_rubrics_edit_addcriteria_addcomponent_select object create" href="#" 
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
foreach($components as $row) { 

	#need to deturmine if the component already exists in the criteria.
	$exists = "NO";
	
	#relay all the things and figure out if the component already is within a list of existing components.
	#TL DR: FOR EACH PARENT FOR EACH EXISTING COMPONENT FOR EACH COMPONENT, IF ANY PARENT MATCHES, HILIGHT THIS COMPONENT.
	foreach($criteriaComponents as $selectedCommponent) {
		
		#check to see if this component is already linked to the criteria. We might not even have to traverse over the tree!
		if($selectedCommponent["COMPONENT_NUM"] == $row["NUM"]) {
			$exists = "MATCH";
			break;
		}
		
		#Ok, fine, it didn't work. Fetch the trees of the existing components...
		$parents = fun_getCompiledSymbolTree($_SESSION["NUM"], $selectedCommponent["COMPONENT_NUM"]);
		
		#...then foreach component in the tree....
		foreach($parents as $parent) {
			
			#if one parent matches, return.
			if($parent["NUM"] == $row["NUM"]) {
				$exists = "INHERITED";
				break 2;
			}
		}
	}?>
	
	<a class="js_rubrics_edit_addcriteria_addcomponent object selectable" href="#" 
			data-componentnum="<?php echo $row["NUM"] ?>" 
			data-criterionnum="<?php echo $CRITERIA_NUM ?>">
		<div class="arrow"></div><?php
		
		if($exists == "MATCH") { 
			?><h2 class="selectedcomponent">This component is specifically selected.</h2><?php
		}
		
		if($exists == "INHERITED") {
			 ?><h2 class="selectedcomponent">This component is inherently selected because<br>a child component is specifically selected.</h2><?php
		}
		
		#Outputs the components
		echo "<h3>(" . $row["SYMBOL"] . ") " . htmlentities($row["NAME"]) . "</h3>";
		
		#And their descriptions
		echo "<div class='monospace'>" . htmlentities($row["DESCRIPTION"]) . "</div>"; ?>
	</a><?php 
}