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

$SEARCH = isset($_POST["SEARCH"]) ? $_POST["SEARCH"] : "";
$WHERE = isset($_POST["WHERE"]) ? $_POST["WHERE"] : "";

###################################

#Sanatize $SEARCH (will be included in PDO, so no sql injection). Removes extra wild cards.
$SEARCH = preg_replace('/%+/', '', $SEARCH); 

#Sanatize $WHERE strictly.
$LOCATION = "STUDENT.USERNAME"; //default.
switch($WHERE) {
	case "FIRST":
		$LOCATION = "STUDENT.FIRST_NAME";
		$WHERE = "First name";
		break;
	case "LAST":
		$LOCATION = "STUDENT.LAST_NAME";
		$WHERE = "Last name";
		break;
	case "USERNAME":
	default:
		$LOCATION = "STUDENT.USERNAME";
		$WHERE = "Username";
		break;
}

if($SEARCH === "") {
	$students = sql_getAllStudents($_SESSION["NUM"]);
} else {
	$students = sql_getAllStudentsBasedOnSearch($_SESSION["NUM"], $LOCATION, $SEARCH);
}


#Output a simple header for searching the database. ?>
<div class="editor">
	<input id="js_accounts_search" class="full" type="text" name="SEARCH" placeholder="Filter">
</div>
<div class="object subtitle">
	<h2>Filter by...</h2>
</div>
<a id="js_accounts_search_username" class="object query" href="#"><h3>Username</h3></a>
<a id="js_accounts_search_last" class="object query" href="#"><h3>Last name</h3></a>
<a id="js_accounts_search_first" class="object query" href="#"><h3>First name</h3></a><?php 


#If we are searching, tell the user what we searched, otherwise just say "Everything"
if(isset($SEARCH) && $SEARCH !== "") { ?>
<div class="object subtitle">
	<h2><?php echo $WHERE . " filter: " . htmlentities($SEARCH); ?></h2>
</div><?php 
} else { ?>
<div class="object subtitle">
	<h2>All linked student accounts:</h2>
</div><?php 
}


#Output a text box so the user can create a new account for a student. ?>
<a id="js_accounts_create" class="object create" href="#"><div class="arrow"></div><h3>Create new account</h3></a><?php 


#Display students
fun_listStudents("js_accounts_student", $students);
?>