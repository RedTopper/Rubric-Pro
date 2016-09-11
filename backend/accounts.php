<?php
$needsAuthentication = true;
$needsAJAX = true;
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

function outputAccounts($data, $search, $where) { ?>

<div class="editor">
	<input id="js_accounts_search" class="full" type="text" name="SEARCH" placeholder="Filter">
</div>
<div class="object subtitle">
	<h2>Filter by...</h2>
</div>
<a id="js_accounts_search_username" class="object query" href="#"><div class="arrow"></div><h1>Username</h1></a>
<a id="js_accounts_search_last" class="object query" href="#"><div class="arrow"></div><h1>Last name</h1></a>
<a id="js_accounts_search_first" class="object query" href="#"><div class="arrow"></div><h1>First name</h1></a>

<div class="object subtitle">
	<h2>Your linked student accounts</h2>
</div>

<a id="js_accounts_create" class="object create" href="#"><div class="arrow"></div><h1>Create new account</h1></a>

<?php if(isset($search) && $search !== "") { ?>
<div class="object subtitle"><h2><?php echo $where . " filter: " . htmlentities($search); ?></h2></div>
<?php } ?>

<?php foreach($data as $row) { ?>
<a class="object selectable" href="#" data-id="<?php echo $row["NUM"] ?>">
<div class="arrow"></div>
<h1>
<?php 
echo "[" . 
$row["USERNAME"] . "]: " . 
$row["LAST_NAME"] . ", " . 
$row["FIRST_NAME"] . 
($row["NICK_NAME"] !== "" ? " (" . $row["NICK_NAME"] . ") " : " "); 
?> 
</h1>
</a>
<?php }

}

switch ($_SESSION["TYPE"]) {
	case "STUDENT":
		showError("Not Allowed", "Students may not edit other student accounts.", "How did you even request this?", 403);
		break;
	case "TEACHER":
		#Connect to database, level 1 is teacher
		if($SEARCH !== "") {
			$stmt = $conn->prepare(
<<<EOT
SELECT STUDENT.NUM, STUDENT.USERNAME, STUDENT.FIRST_NAME, STUDENT.LAST_NAME, STUDENT.NICK_NAME
FROM STUDENT
JOIN TEACHES ON STUDENT.NUM = TEACHES.STUDENT_NUM
JOIN TEACHER ON TEACHES.TEACHER_NUM = :teacherID
WHERE $location LIKE CONCAT('%',:search,'%') 
EOT
			);
			$stmt->execute(array('teacherID' => $_SESSION["NUM"], 'search' => $SEARCH));	
		} else {
			$stmt = $conn->prepare( 
<<<EOT
SELECT STUDENT.NUM, STUDENT.USERNAME, STUDENT.FIRST_NAME, STUDENT.LAST_NAME, STUDENT.NICK_NAME 
FROM STUDENT
JOIN TEACHES ON STUDENT.NUM = TEACHES.STUDENT_NUM
JOIN TEACHER ON TEACHES.TEACHER_NUM = :teacherID
EOT
			);
			$stmt->execute(array('teacherID' => $_SESSION["NUM"]));	
		}
		$data = $stmt->fetchAll();
		outputAccounts($data, $SEARCH, $WHERE);
		break;
}
?>