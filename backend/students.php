<?php
$needsAuthentication = true;
include "db.php";
$SEARCH = $_POST["SEARCH"];

switch ($_SESSION["TYPE"]) {
	case 0:
		break;
	case 1:
		break;
	case 2:
		#Connect to database
		if(isset($SEARCH)) {
			$SEARCH = preg_replace('/%+/', '', $SEARCH); //remove wildcards that user entered.
			$stmt = $conn->prepare("SELECT USERNAME, PARENT, ID FROM ACCOUNTS WHERE PARENT = :username AND USERNAME LIKE CONCAT('%',CONCAT(:search,'%'))");
			$stmt->execute(array('username' => $_SESSION["USERNAME"], 'search' => $SEARCH));	
		} else {
			$stmt = $conn->prepare("SELECT USERNAME, PARENT, ID FROM ACCOUNTS WHERE PARENT = :username");
			$stmt->execute(array('username' => $_SESSION["USERNAME"]));	
		}
		$data = $stmt->fetchAll();
?> 
<div class="editor">
	<input id="js_students_search" class="full" type="text" name="SEARCH" placeholder="Filter Students">
</div>
<?php
		foreach($data as $row) { 
?>
<a class="object selectable" href="#" data-id="<?php echo $row["ID"] ?>"><div class="arrow"></div><h1><?php echo $row["USERNAME"] ?></h1></a>
<?php			
		}
?>
<a id="js_students_create" class="object create" href="#"><div class="arrow"></div><h1>Create new student account</h1></a>
<?php
		break;
}
?>