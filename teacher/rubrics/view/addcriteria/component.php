<?php  die();
#Libraries.
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
$needsFunction = true;
$needsSQL = true;
include "../restricted/db.php";
include "../restricted/functions.php";
include "../restricted/sql.php";

$COMPONENT = isset($_POST["COMPONENT"]) ? $_POST["COMPONENT"] : null;
$RUBRIC_NUM = isset($_POST["RUBRIC_NUM"]) ? $_POST["RUBRIC_NUM"] : null;
$CRITERIA_NUM = isset($_POST["CRITERIA_NUM"]) ? $_POST["CRITERIA_NUM"] : null;

#Modification mode is true when we are not requesting components from the rubric editor.
$MODIFICATION_MODE = $RUBRIC_NUM === null && $CRITERIA_NUM === null;

#Validate that the component can be null or a number greater than 0
if(!($COMPONENT == null || is_numeric($COMPONENT) && $COMPONENT > 0)) {
	showError("Whoops", "I didn't quite understand the request...", "Sorry about that!", 400);
}

#List of selected components
$components = null;

#If it's null, request the root elements (they are null)
if($COMPONENT === null) {
	$stmt = $conn->prepare("SELECT NUM, TEACHER_NUM, PARENT_NUM, SYMBOL, NAME, DESCRIPTION FROM COMPONENT WHERE TEACHER_NUM = :teacherNum AND PARENT_NUM IS NULL");
	$stmt->execute(array('teacherNum' => $_SESSION["NUM"]));

#Otherwise we need to fetch the elemetns that the user requested as well as it's parent.
} else {

	#Get parent information
	$stmt = $conn->prepare("SELECT NUM, TEACHER_NUM, PARENT_NUM, SYMBOL, NAME, DESCRIPTION FROM COMPONENT WHERE TEACHER_NUM = :teacherNum AND NUM = :componentNum");
	$stmt->execute(array('teacherNum' => $_SESSION["NUM"], 'componentNum' => $COMPONENT));
	$parent = $stmt->fetch();
	$count = $stmt->rowCount();

	#If we do not have a matching parent show an error.
	if($count != 1) {
		?><div class="title"><h3>Something happened</h3></div><?php #show title because nothing in this section has one.
		showError("Whoops!", "There is no matching parent.", "Sorry about that!", 400);
	}

	#Get all components from that parent
	$stmt = $conn->prepare("SELECT NUM, TEACHER_NUM, PARENT_NUM, SYMBOL, NAME, DESCRIPTION FROM COMPONENT WHERE TEACHER_NUM = :teacherNum AND PARENT_NUM = :componentNum ORDER BY SYMBOL");
	$stmt->execute(array('teacherNum' => $_SESSION["NUM"], 'componentNum' => $COMPONENT));
}

#Fetch everything (data array).
$data = $stmt->fetchAll();

#If we are not in modification mode, we need to figure out what components are already a part of a criteria!
if(!$MODIFICATION_MODE) {
	#This is where it gets fun though. We need to see if the component that's being added to the list
	#is already in the criteria that we are adding so the user has some visual basis to see what's already added.
	$stmt = $conn->prepare("SELECT COMPONENT_NUM FROM CRITERION WHERE RUBRIC_CRITERIA_NUM = :criteria");
	$stmt->execute(array('criteria' => $CRITERIA_NUM));
	$existingComponentsData = $stmt->fetchAll();
}

#If we are at the root component
if($COMPONENT === null) {
	
	#Title for the root components. ?>
	<div class="object subtitle">
		<h2>Your root components:</h2>
	</div>

	<?php
	#If we are requesting the components from the rubric editor, restrict creation and
	#deletion of components.
	if($MODIFICATION_MODE) { ?>
		<a class="js_component_create object create" href="#"><div class="arrow"></div>
			<h3>Create new "Root Component"</h3>
		</a><?php
	} else { ?>
		<a class="js_rubrics_edit_addcriteria_destroycomponents object destroy" href="#"><div class="arrow"></div>
			<h3>Remove all components from this criteria</h3>
		</a><?php
	}
} else {
	
	#Title for the sub components. ?>
	<div class="title">
		<h1><?php echo htmlentities($parent["NAME"]); ?></h1>
	</div>
	<div class="object subtitle">
		<h2>Components</h2>
	</div>

	<?php
	#In modification mode, show creation of components and destruction of components.
	if($MODIFICATION_MODE) { ?>
		<a class="js_component_create object create" href="#" data-num="<?php echo $parent["NUM"]; ?>"><div class="arrow"></div>
			<h3>New component in "<?php echo htmlentities($parent["NAME"]); ?>"</h3>
		</a>
		<a class="js_component_destroy object destroy" href="#" data-num="<?php echo $parent["NUM"]; ?>"><div class="arrow"></div>
			<h3>Destroy "<?php echo htmlentities($parent["NAME"]); ?>"</h3>
		</a><?php 

	#Otherwise just show the select component button.
	} else { ?>
		
		<a class="js_rubrics_edit_addcriteria_addcomponent_select object create" href="#" data-num="<?php echo $parent["NUM"] ?>" 
		data-rubricnum="<?php echo $RUBRIC_NUM ?>" data-criterionnum="<?php echo $CRITERIA_NUM ?>"><div class="arrow"></div>
			<h3>Select "<?php echo htmlentities($parent["NAME"]); ?>"</h3>
		</a><?php
	}
}

#Display all components from the data array.
foreach($data as $row) { 

	#For non modification mode, we need to deturmine if the component already exists in the criteria.
	$exists = "NO";
	
	
	if($MODIFICATION_MODE) {
		
		#If we are modifying the components, then we don't need to relay the rubric and component number. ?>
		<a class="js_components_select object selectable" href="#" data-num="<?php echo $row["NUM"] ?>"><?php 
	
	} else { 
	
		#Otherwise, relay all the things and figure out if the component already is within a list of existing components. ?>
		<a class="js_components_select object selectable" href="#" 
		data-num="<?php echo $row["NUM"] ?>" data-rubricnum="<?php echo $RUBRIC_NUM ?>" data-criterionnum="<?php echo $CRITERIA_NUM ?>"><?php 
		
		#TL DR: FOR EACH PARENT FOR EACH EXISTING COMPONENT FOR EACH COMPONENT, IF ANY PARENT MATCHES, HILIGHT THIS COMPONENT.
		foreach($existingComponentsData as $existingComponents) {
			
			#check to see if this component is already linked to the criteria. We might not even have to traverse over the tree!
			if($existingComponents["COMPONENT_NUM"] == $row["NUM"]) {
				$exists = "MATCH";
				break;
			}
			
			#Ok, fine, it didn't work. Fetch the trees of the existing components...
			$parents = getCompiledSymbolTree($_SESSION["NUM"], $existingComponents["COMPONENT_NUM"]);
			
			#...then foreach component in the tree....
			foreach($parents as $parent) {
				
				#if one parent matches, return.
				if($parent["NUM"] == $row["NUM"]) {
					$exists = "INHERITED";
					break 2;
				}
			}
		}
	}?>
	
	
	<div class="arrow"></div><?php
		if($exists == "MATCH") { ?>
			<h2 class="selectedcomponent">This component is specifically selected.</h2><?php
		}
		if($exists == "INHERITED") { ?>
			<h2 class="selectedcomponent">This component is inherently selected because<br> a child component is specifically selected.</h2><?php
		} ?>
		<h3><?php 
			#Outputs the components
			echo "(" . $row["SYMBOL"] . ") " . htmlentities($row["NAME"]); ?>
		</h3>
		<div class="monospace"><?php
		
			#And their descriptions
			echo htmlentities($row["DESCRIPTION"]); ?>
		</div>
	</a><?php 
}

#If we are at root, then display help information to the user.
if($COMPONENT === null && $MODIFICATION_MODE) {
?>
<div class="object subtext spacer"></div>
<div class="object subtitle">
	<h2>How to use <br>"The Component Editor"</h2>
</div>
<div class="object subtext">
	<p>Welcome to the component editor!
	<p>Here you can specify parts of your curriculum so you can see the progress of your class as it relates to each component!
	<p>To use this feature to the best of your ability, you should start with a broad subject in the "component root" (That's the section you are currently
	looking at right now).
	<p>For example, a root component might look like this:
	<ul>
		<li>Symbol: "CIS"
		<li>Name: "Computer Science"
		<li>Description: "My computer science curriculum!"
	</ul>
	<p>You can then add a component to your component to better section your class.
	<p>Following the first example, you could do something like this:
	<ul>
		<li>Parent: "CIS"
		<li>Symbol: "Chapter 1"
		<li>Name: "Design"
		<li>Description: "Understanding of the concepts of design, formatting, and structure of Programming"
	</ul>
	<p>You can even add components to those components!
</div>
<div class="object subtitle">
	<h2>Getting started...</h2>
</div>
<div class="object subtext">
	<ul>
		<li>Use a class name as your "root component". For example: 
		<ul>
			<li>"History"
			<li>"AP Calculus"
			<li>"Computer Science"
			<li>"Intro to Psych"
			<li>etc...
		</ul>
		<li>Put chapter names inside your "root component". For example: 
		<ul>
			<li>"Chapter 1: Understanding mechanics"
			<li>"Chapter 2: Class design"
			<li>"Chapter 4: Civil War"
			<li>"Part 1: Derivitives"
			<li>"Outcome 2: Fitness"
			<li>etc...
		</ul>
		<li>Put section names inside your chapter names. For example: 
		<ul>
			<li>"Section A: The Gear"
			<li>"Part A: The for loop"
			<li>"B: The Union and the Confederates"
			<li>"VI: Limits"
			<li>etc...
		</ul>
		<li>It's also possible that your curriculum is divided into components for you. Check your course outline for more details.
	</ul>
</div>
<?php
}
?>