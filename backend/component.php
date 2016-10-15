<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";
$COMPONENT = isset($_POST["COMPONENT"]) ? $_POST["COMPONENT"] : null;
$RUBRIC_NUM = isset($_POST["RUBRIC_NUM"]) ? $_POST["RUBRIC_NUM"] : null;
$CRITERIA_NUM = isset($_POST["CRITERIA_NUM"]) ? $_POST["CRITERIA_NUM"] : null;

$MODIFICATION_MODE = $RUBRIC_NUM === null && $CRITERIA_NUM === null;


#Validate that the component can be null or a number greater than 0
if(!($COMPONENT == null || is_numeric($COMPONENT) && $COMPONENT > 0)) {
	showError("Whoops", "I didn't quite understand the request...", "Sorry about that!", 400);
}

#If it's null, request the root elements (they are null)
if($COMPONENT === null) {
$stmt = $conn->prepare( 
<<<SQL
SELECT NUM, TEACHER_NUM, PARENT_NUM, SYMBOL, NAME, DESCRIPTION
FROM COMPONENT
WHERE TEACHER_NUM = :teacherNum AND PARENT_NUM IS NULL
SQL
);
$stmt->execute(array('teacherNum' => $_SESSION["NUM"]));

#Otherwise we need to fetch the elemetns that the user requested as well as it's parent.
} else {

#Get parent information
$stmt = $conn->prepare(<<<SQL
SELECT NUM, TEACHER_NUM, PARENT_NUM, SYMBOL, NAME, DESCRIPTION
FROM COMPONENT
WHERE TEACHER_NUM = :teacherNum AND NUM = :componentNum
SQL
);
$stmt->execute(array('teacherNum' => $_SESSION["NUM"], 'componentNum' => $COMPONENT));
$parent = $stmt->fetch();
$count = $stmt->rowCount();

#If we do not have a matching parent show an error.
if($count != 1) {
?>
<div class="title">
	<h3>Something happened</h3>
</div>
<?php
showError("Whoops!", "There is no matching parent.", "Sorry about that!", 400);
}

#Get all components from that parent
$stmt = $conn->prepare(<<<SQL
SELECT NUM, TEACHER_NUM, PARENT_NUM, SYMBOL, NAME, DESCRIPTION
FROM COMPONENT
WHERE TEACHER_NUM = :teacherNum AND PARENT_NUM = :componentNum
ORDER BY SYMBOL
SQL
);
$stmt->execute(array('teacherNum' => $_SESSION["NUM"], 'componentNum' => $COMPONENT));
}

#Fetch everything (data array).
$data = $stmt->fetchAll();

if($COMPONENT === null) {
	
#Title for the root components.
?>
<div class="object subtitle">
	<h2>Your root components:</h2>
</div>

<?php
#If we are requesting the components from the rubric editor, restrict creation and
#deletion of components.
if($MODIFICATION_MODE) { ?>
<a class="js_component_create object create" href="#"><div class="arrow"></div>
	<h3>Create new "Root Component"</h3>
</a>
<?php
}
} else {
	
#Title for the sub components.
?>
<div class="title">
	<h1><?php echo htmlentities($parent["NAME"]); ?></h1>
</div>
<div class="object subtitle">
	<h2>Components</h2>
</div>

<?php
if($MODIFICATION_MODE) { ?>
<a class="js_component_create object create" href="#" data-num="<?php echo $parent["NUM"]; ?>"><div class="arrow"></div>
	<h3>New component in "<?php echo htmlentities($parent["NAME"]); ?>"</h3>
</a>
<a class="js_component_destroy object destroy" href="#" data-num="<?php echo $parent["NUM"]; ?>"><div class="arrow"></div>
	<h3>Destroy "<?php echo htmlentities($parent["NAME"]); ?>"</h3>
</a>
<?php } else { ?>
<a class="js_rubrics_edit_addcriteria_addcomponent_select object create" href="#" data-num="<?php echo $parent["NUM"]; ?>"><div class="arrow"></div>
	<h3>Select "<?php echo htmlentities($parent["NAME"]); ?>"</h3>
</a>
<?php
}
}

#Display all components from the data array.
foreach($data as $row) { 
	if($MODIFICATION_MODE) {
		
	#If we are modifying the components, then we don't need to relay the rubric and component number. ?>
	<a class="js_components_select object selectable" href="#" data-num="<?php echo $row["NUM"] ?>">
	<?php } else { 
	
	#Otherwise, relay all the things. ?>
	<a class="js_components_select object selectable" href="#" 
	data-num="<?php echo $row["NUM"] ?>" data-rubricnum="<?php echo $RUBRIC_NUM ?>" data-criterionnum="<?php echo $CRITERIA_NUM ?>">
	<?php } ?>
	
	
	<div class="arrow"></div>
		<h3>
		<?php 

		#Outputs the components
		echo "(" . $row["SYMBOL"] . ") " . htmlentities($row["NAME"]);
		?>
		</h3>
		<div class="monospace">
		<?php
		
		#And their descriptions
		echo htmlentities($row["DESCRIPTION"]);
		?>
		</div>
	</a>
	<?php 
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