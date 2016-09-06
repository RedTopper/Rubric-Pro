<?php
$needsAuthentication = false;
include "backend/db.php";

#Display the HTML login screen. Pass the error if needed.
function displayLogin($error = null, $success = null) {
?>
<!DOCTYPE html>
<head>
	<title>Rubric Pro: Login</title>
	<link rel="stylesheet" href="css/login.css"> 
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet"> 
	<meta charset="UTF-8">
	<meta name="author" content="Aaron Walter (2016)">
	<meta name="description" content="Log in to Rubric Pro.">
</head>
<body>
	<div id="login">
		<img id="logo" src="images/logo.png" alt="Rubric Pro">
		<form method="post">
		  <h2>Username:</h2>
		  <input type="text" name="USERNAME" placeholder="rubricpro">
		  <h2>Password:</h2>
		  <input type="password" name="PASSWORD" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
		  <button type="submit" name="GO" value="ENTER">Enter</button>
		</form> 
	</div>
<?php if(isset($success)) { #If there is an error, add a div. ?>
	<div id="changepassword">
		<?php echo $success; ?> 
	</div>
<?php } ?>
<?php if(isset($error)) { #If there is an error, add a div. ?>
	<div id="error">
		<?php echo $error; ?> 
	</div>
<?php } ?>
</body>
<?php

die();
}

#Displayed when an admin or teacher creates an account, but there is no password associated with it yet.
function displayChangePassword($error = null) {
?>
<!DOCTYPE html>
<head>
	<title>Rubric Pro: Change Password</title>
	<link rel="stylesheet" href="css/login.css"> 
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet"> 
	<meta charset="UTF-8">
	<meta name="author" content="Aaron Walter (2016)">
	<meta name="description" content="Change your password.">
</head>
<body>
	<div id="login">
		<img id="logo" src="images/logo.png" alt="Rubric Pro">
		<form method="post">
		  <h2>Username:</h2>
		  <input type="text" name="USERNAME" value="<?php echo htmlspecialchars($_SESSION["TEMP_USERNAME"]); ?>" disabled>
		  <h2>Password:</h2>
		  <input type="password" name="PASSWORD1" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
		  <h2>Retype password:</h2>
		  <input type="password" name="PASSWORD2" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
		  <button type="submit" name="GO" value="CHANGE">Change password</button>
		  <button type="submit" name="GO" value="BACK">Go back</button>
		</form> 
	</div>
	<div id="changepassword">
		You need to change your password before you can log in.
	</div>
<?php if(isset($error)) { #If there is an error, add a div. ?>
	<div id="error">
		<?php echo $error; ?> 
	</div>
<?php } ?>
</body>
<?php

die();
}

if (!empty($_POST)) {
	if(!isset($_SESSION["TEMP_USERNAME"])) {
		if(!(isset($_POST["USERNAME"]) && isset($_POST["PASSWORD"]))) {
			displayLogin();
		}
		
		if($_POST["USERNAME"] === '') {
			displayLogin();
		}
		
		$stmt = $conn->prepare("SELECT ID, USERNAME, PASSWORD, TYPE FROM ACCOUNTS WHERE USERNAME = :username");
		$stmt->execute(array('username' => $_POST["USERNAME"]));
		$row = $stmt->fetch();
		
		if($row["PASSWORD"] === "CHANGE") {
			$_SESSION['TEMP_USERNAME'] = $_POST["USERNAME"]; 
			$_SESSION['TEMP_ID'] = $row["ID"];
			displayChangePassword();
		}
		
		if(!(password_verify($_POST["PASSWORD"], $row["PASSWORD"]))) {
			displayLogin("The username or password is incorrect!"); 
		}
		
		#Initializing Session
		$_SESSION['USERNAME'] = $row["USERNAME"]; 
		$_SESSION['TYPE'] = $row["TYPE"];
		$_SESSION['TIMESTAMP'] = date("Y-m-d H:i:s");
		$_SESSION['VALID'] = true;
	} else {
		if(isset($_POST["GO"]) && $_POST["GO"] === "BACK") {
			unset($_SESSION['TEMP_USERNAME']);
			unset($_SESSION['TEMP_ID']);
			displayLogin(null, "Remember to change your password later!");
		}
		
		if(!(isset($_POST["PASSWORD1"]) && isset($_POST["PASSWORD2"]))) {
			displayChangePassword();
		}
		
		if($_POST["PASSWORD1"] !== $_POST["PASSWORD2"]) {
			displayChangePassword("The new passwords do not match.");
		}
		
		#Password length can't be too short
		if(strlen($_POST["PASSWORD1"]) <= 6) {
			displayChangePassword("Your new password needs to be longer than 6 characters!");
		} 
		
		#Password length can't be too long. If it is, BCRYPT does some really funkey stuff.
		if(strlen($_POST["PASSWORD1"]) > 70) {
			displayChangePassword("I know you like security, but you'll break things if your new password is that long.");
		} 
		
		$options = ['cost' => 12];
		$stmt = $conn->prepare("UPDATE ACCOUNTS SET PASSWORD = :password WHERE ID = :id");
		$stmt->execute(array('password' => password_hash($_POST["PASSWORD1"], PASSWORD_BCRYPT, $options), 'id' => $_SESSION["TEMP_ID"]));
		
		unset($_SESSION['TEMP_USERNAME']);
		unset($_SESSION['TEMP_ID']);
		
		displayLogin(null, "You changed your password!");
	}
}

if(isset($_SESSION['USERNAME']) && $_SESSION['VALID']){
	header("location: /edit.php");
	die();
}

displayLogin();
