<?php
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsFunction = true;
$needsSQL = true;
include "../restricted/db.php";
include "../restricted/functions.php";
include "../restricted/sql.php";

$COMPONENT_NUM = isset($_POST["COMPONENT_NUM"]) ? $_POST["COMPONENT_NUM"] : null;

#Validate that the component can be null or a number greater than 0
if(!($COMPONENT_NUM == null || is_numeric($COMPONENT_NUM) && $COMPONENT_NUM > 0)) {
	db_showError("Whoops", "I didn't quite understand the request...", "Sorry about that!", 400);
}

#List of selected components
$components = null;

#If there is a parent, we'll set this to the parent's information
$parent = null;

if($COMPONENT_NUM === null) {
	
	#If it's null, request the root elements
	$components = sql_getAllRootComponents($_SESSION["NUM"]);
	
	#Title for the root components. ?>
	<div class="object subtitle">
		<a href="#" data-document="COMPONENTEDITOR" class="js_help"><img class="help" src="images/help.svg" alt="Help" title="Help"></a>
		<h2>Your root components:</h2>
	</div>
	<a class="js_components_create object create" href="#">
		<div class="arrow"></div>
		<h3>Create new "Root Component"</h3>
	</a><?php
} else {
	
	#Otherwise we need to fetch the elemetns that the user requested as well as it's parent.
	$parent = sql_getComponent($_SESSION["NUM"], $COMPONENT_NUM);

	#If we do not have a matching parent show an error.
	if($parent === null) { ?>
		<div class="title"><h1>Something happened</h1></div><?php #show title because nothing in this section has one.
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
	<a class="js_components_create object create" href="#" data-componentnum="<?php echo $parent["NUM"]; ?>">
		<div class="arrow"></div>
		<h3>New component in "<?php echo htmlentities($parent["NAME"]); ?>"</h3>
	</a>
	<a class="js_component_destroy object destroy" href="#" data-componentnum="<?php echo $parent["NUM"]; ?>">
		<div class="arrow"></div>
		<h3>Destroy "<?php echo htmlentities($parent["NAME"]); ?>"</h3>
	</a><?php 
}

if($components === null) { ?>
	<div class="object subtext">
		<p>There's nothing here. 
		<p>You can create a new component with the button above.
	</div>
	<?php die();
}

#Display all components from the data array.
foreach($components as $comp) { 
		
	#If we are modifying the components, then we don't need to relay the rubric and component number. ?>
	<a class="js_components_select object selectable" href="#" data-componentnum="<?php echo $comp["NUM"] ?>">
	
	<div class="arrow"></div>
		<h3><?php 
			#Outputs the components
			echo "(" . $comp["SYMBOL"] . ") " . htmlentities($comp["NAME"]); ?>
		</h3>
		<div class="monospace"><?php
		
			#And their descriptions
			echo htmlentities($comp["DESCRIPTION"]); ?>
		</div>
	</a><?php 
}