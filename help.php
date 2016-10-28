<?php
$needsAuthentication = false;
$needsAJAX = true;
$needsTeacher = false;
$needsFunction = true;
include "./restricted/db.php";
include "./restricted/functions.php";

$DOCUMENT = isset($_POST["DOCUMENT"]) ? $_POST["DOCUMENT"] : "";

switch($DOCUMENT) {
	case "MAXPOINTSPERCRITERIA": ?>
	<div class="object subtitle"><h2>About "Maximum points per criteria"</h2></div>
	<div class="object subtext">
		<p>Maximum points per criteria represents the maximum amount of points that a student can acheive per row on a rubric.
		<p>If you plan to score a student 9/10 in a criteria, the maximum points per criteria should be 10.</p>
	</div>
	<?php break;
	
	case "CRITERIA": ?>
	<div class="object subtitle">
		<h2>About criteria</h2>
	</div><?php
	#Quick table to show the user what they are editing
	fun_createExampleTableCriteria(); 
	break;
	
	case "QUALITY": ?>
	<div class="object subtitle">
		<h2>About qualities</h2>
	</div><?php
	#Quick table to show the user what they are editing.
	fun_createExampleTableQualities();
	break;
	
	case "COMPONENTEDITOR": ?>
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
	</div><?php break;
	
	case "CLASSMGMT": ?>
	<div class="object subtitle">
		<h2>About dropping students from classes</h2>
	</div>
	<div class="object subtext">
		<p>Here's what'll happen:
		<p>The student...
		<ul>
		<li><b>CAN</b> be added back to this list</li>
		<li><b>WILL</b> still effect your graded criteria</li>
		<li><b>WILL NOT</b> be able to view their projects</li>
		<li><b>WILL NOT</b> have any data lost</li>
		<li><b>WILL NOT</b> be able to access their grades from this class</li>
		</ul>
		<p>To undo these changes, add them back to this class by selecting "Bind this student to a class"
	</div><?php break;
	
	default: ?>
	<div class="object subtitle">
		<h2>No help document found!</h2>
	</div>
	<div class="object subtext">
		<p>There is no information on this subject yet.
	</div>
	<?php break;
}