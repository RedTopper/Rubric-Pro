<?php
$needsAuthentication = true;
$needsAJAX = true;
include "db.php";
$SEARCH = $_POST["SEARCH"];

function outputAccounts($data, $search, $type) { ?> 
<div class="editor">
	<input id="js_accounts_search" class="full" type="text" name="SEARCH" placeholder="Filter Accounts">
</div>
<?php if(isset($search) && $search !== "") { ?>
<div class="object subtitle"><h2>Filter: <?php echo htmlentities($search); ?></h2></div>
<?php } foreach($data as $row) { ?>
<a class="object selectable" href="#" data-id="<?php echo $row["ID"] ?>"><div class="arrow"></div><h1><?php 

switch ($type) {
	case 2:
		echo $row["PARENT"] . ".";
	case 1:
		echo $row["USERNAME"];
}

?></h1></a>
<?php } ?>
<a id="js_accounts_create" class="object create" href="#"><div class="arrow"></div><h1>Create new account</h1></a>
<?php
}

switch ($_SESSION["TYPE"]) {
	case 0:
		showError("Not Allowed", "Students may not edit other student accounts.", "How did you even request this?", 403);
		break;
	case 1:
		#Connect to database, level 1 is teacher
		if(isset($SEARCH)) {
			$SEARCH = preg_replace('/%+/', '', $SEARCH); //remove wildcards that user entered.
			$stmt = $conn->prepare("SELECT USERNAME, PARENT, ID FROM ACCOUNTS WHERE PARENT = :username AND USERNAME LIKE CONCAT('%',:search,'%')");
			$stmt->execute(array('username' => $_SESSION["USERNAME"], 'search' => $SEARCH));	
		} else {
			$stmt = $conn->prepare("SELECT USERNAME, PARENT, ID FROM ACCOUNTS WHERE PARENT = :username");
			$stmt->execute(array('username' => $_SESSION["USERNAME"]));	
		}
		$data = $stmt->fetchAll();
		outputAccounts($data, $SEARCH, $_SESSION["TYPE"]);
		break;
	case 2:
		#Connect to database, level 2 is root, they can see all accounts.
		if(isset($SEARCH)) {
			$SEARCH = preg_replace('/%+/', '', $SEARCH); //remove wildcards that user entered.
			$stmt = $conn->prepare("SELECT USERNAME, PARENT, ID FROM ACCOUNTS WHERE USERNAME LIKE CONCAT('%',:search,'%')");
			$stmt->execute(array('search' => $SEARCH));	
		} else {
			$stmt = $conn->prepare("SELECT USERNAME, PARENT, ID FROM ACCOUNTS");
			$stmt->execute();	
		}
		$data = $stmt->fetchAll();
		outputAccounts($data, $SEARCH, $_SESSION["TYPE"]);
		break;
}
?>