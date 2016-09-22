<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";
$SEARCH = isset($_POST["SEARCH"]) ? $_POST["SEARCH"] : "";
$WHERE = isset($_POST["WHERE"]) ? $_POST["WHERE"] : "";

//Sanatize $SEARCH (will be included in PDO, so no sql injection). Removes extra wild cards.
$SEARCH = preg_replace('/%+/', '', $SEARCH); 

//Sanatize $WHERE strictly.
$location = "STUDENT.USERNAME"; //default.
switch($WHERE) {
	case "first":
	case "FIRST":
		$location = "STUDENT.FIRST_NAME";
		$WHERE = "First name";
		break;
	case "last":
	case "LAST":
		$location = "STUDENT.LAST_NAME";
		$WHERE = "Last name";
		break;
	case "username":
	case "USERNAME":
	default:
		$location = "STUDENT.USERNAME";
		$WHERE = "Username";
		break;
}

function outputAccounts($data, $search, $where) { 


//Output a simple header for searching the database. 
?>
<div class="editor">
	<input id="js_accounts_search" class="full" type="text" name="SEARCH" placeholder="Filter">
</div>
<div class="object subtitle">
	<h2>Filter by...</h2>
</div>
<a id="js_accounts_search_username" class="object query" href="#"><h1>Username</h1></a>
<a id="js_accounts_search_last" class="object query" href="#"><h1>Last name</h1></a>
<a id="js_accounts_search_first" class="object query" href="#"><h1>First name</h1></a>
<?php 


//If we are searching, tell the user what we searched, otherwise just say "Everything"
if(isset($search) && $search !== "") { ?>
<div class="object subtitle">
	<h2><?php echo $where . " filter: " . htmlentities($search); ?></h2>
</div>
<?php } else { ?>
<div class="object subtitle">
	<h2>All linked student accounts:</h2>
</div>
<?php } 


//Output a text box so the user can create a new account for a student.
?>
<a id="js_accounts_create" class="object create" href="#"><div class="arrow"></div><h1>Create new account</h1></a>
<?php 


//Loop through each student and give it an anchor tag. The class js_accounts_student class binds these to access.js
foreach($data as $row) { 
?>
<a class="js_accounts_student object selectable" href="#" data-num="<?php echo $row["NUM"] ?>">
<div class="arrow"></div>
<h1>
<?php 


//Outpus the actual students.
echo "<div class='monospace'>[" . 
htmlentities($row["USERNAME"]) . "]:</div> " . 
htmlentities($row["LAST_NAME"]) . ", " . 
htmlentities($row["FIRST_NAME"]) . 
htmlentities(($row["NICK_NAME"] !== "" ? " (" . $row["NICK_NAME"] . ") " : " ")); 
?> 
</h1>
</a>
<?php }

}

if($SEARCH !== "") {
	$stmt = $conn->prepare(
<<<SQL
SELECT STUDENT.NUM, STUDENT.USERNAME, STUDENT.FIRST_NAME, STUDENT.LAST_NAME, STUDENT.NICK_NAME
FROM STUDENT
JOIN TEACHES ON STUDENT.NUM = TEACHES.STUDENT_NUM
WHERE
TEACHES.TEACHER_NUM = :teacherID AND
$location LIKE CONCAT('%',:search,'%') 
ORDER BY STUDENT.LAST_NAME, STUDENT.FIRST_NAME
SQL
	);
	$stmt->execute(array('teacherID' => $_SESSION["NUM"], 'search' => $SEARCH));	
} else {
	$stmt = $conn->prepare( 
<<<SQL
SELECT STUDENT.NUM, STUDENT.USERNAME, STUDENT.FIRST_NAME, STUDENT.LAST_NAME, STUDENT.NICK_NAME 
FROM STUDENT
JOIN TEACHES ON STUDENT.NUM = TEACHES.STUDENT_NUM
WHERE 
TEACHES.TEACHER_NUM = :teacherID
ORDER BY STUDENT.LAST_NAME, STUDENT.FIRST_NAME
SQL
	);
	$stmt->execute(array('teacherID' => $_SESSION["NUM"]));	
}
$data = $stmt->fetchAll();
outputAccounts($data, $SEARCH, $WHERE);
?>