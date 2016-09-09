<?php
$needsAuthentication = false;
$needsAJAX = false;
include "backend/db.php";
$USERNAME = $_POST["USERNAME"];
$PASSWORD = $_POST["PASSWORD"];
$PASSWORD1 = $_POST["PASSWORD1"];
$PASSWORD2 = $_POST["PASSWORD2"];
$GO = $_POST["GO"];

/*
A little information on the variables above:

By declairing the $_POST[] variables to actual variables, I can quickly
see what the client should be sending to this PHP file, should anything
change. This also allows sanitization to occur directly after the 
declarations if needed.
*/

#Static vars so the code can be read better.
$LOGIN = true;
$CHANGE = false;

#Display the HTML login screen. Pass the error if needed.
function display($login, $error, $success) {
?>
<!DOCTYPE html>
<head>
<?php if($login) { ?>
	<title>Rubric Pro: Login</title>
<?php } else { ?>
	<title>Rubric Pro: Change Password</title>
<?php } ?>
	<link rel="stylesheet" href="css/login.css"> 
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet"> 
	<meta charset="UTF-8">
	<meta name="author" content="Aaron Walter (2016)">
	<meta name="description" content="Log in to Rubric Pro.">
</head>
<body>
	<div id="login">
		<img id="logo" src="images/logo.png" alt="Rubric Pro">
<?php if($login) { ?>
		<form method="post">
		  <h2>Username:</h2>
		  <input type="text" name="USERNAME" placeholder="rubricpro">
		  <h2>Password:</h2>
		  <input type="password" name="PASSWORD" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
		  <button type="submit" name="GO" value="ENTER">Enter</button>
		</form> 
<?php } else { ?>
		<form method="post">
		  <h2>Username:</h2>
		  <input type="text" name="USERNAME" value="<?php echo htmlentities($_SESSION["TEMP_USERNAME"]); ?>" disabled>
		  <h2>Password:</h2>
		  <input type="password" name="PASSWORD1" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
		  <h2>Retype password:</h2>
		  <input type="password" name="PASSWORD2" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
		  <button type="submit" name="GO" value="CHANGE">Change password</button>
		  <button type="submit" name="GO" value="BACK">Go back</button>
		</form> 
<?php } ?>
	</div> 

<?php if(isset($success)) { #If there is a success message, show it. ?>
	<div id="success">
		<?php echo $success; ?> 
	</div>
<?php } ?>

<?php if(isset($error)) { #If there is an error message, show it. ?>
	<div id="error">
		<?php echo $error; ?> 
	</div>
<?php } ?>

</body>
<?php
die();
}

if(!isset($_SESSION["TEMP_USERNAME"])) {
	#Check if empty
	if(!(isset($USERNAME) && isset($PASSWORD))) {
		display($LOGIN, null, null);
	}
	
	#Check if empty
	if($USERNAME === '') {
		display($LOGIN, null, null);
	}
	
	#Connect to database
	$stmt = $conn->prepare("SELECT ID, USERNAME, PASSWORD, TYPE, PARENT FROM ACCOUNTS WHERE USERNAME = :username");
	$stmt->execute(array('username' => $USERNAME));
	$row = $stmt->fetch();
	
	#If the database says "CHANGE", then the user needs to set their password before they log in.
	if($row["PASSWORD"] === "CHANGE") {
		$_SESSION['TEMP_USERNAME'] = $USERNAME; 
		$_SESSION['TEMP_ID'] = $row["ID"];
		display($CHANGE, null, "You need to change your password before you can log in.");
	}
	
	#Verify password
	if(!(password_verify($PASSWORD, $row["PASSWORD"]))) {
		display($LOGIN, "The username or password is incorrect!", null); 
	}
	
	#Initialize Session
	$_SESSION['USERNAME'] = $row["USERNAME"]; 
	$_SESSION['TYPE'] = $row["TYPE"];
	$_SESSION['PARENT'] = $row["PARENT"];
	$_SESSION['TIMESTAMP'] = date("Y-m-d H:i:s");
	$_SESSION['VALID'] = true;
} else {
	#Go back if go back is set.
	if(isset($GO) && $GO === "BACK") {
		session_destroy();
		display($LOGIN, null, "Remember to change your password later!");
	}
	
	#Check if passwords are set
	if(!(isset($PASSWORD1) && isset($PASSWORD2))) {
		display($CHANGE, null, null);
	}
	
	#Check if the passwords match
	if($PASSWORD1 !== $PASSWORD2) {
		display($CHANGE, "The new passwords do not match.", null);
	}
	
	#Password length can't be too short
	if(strlen($PASSWORD1) <= 6) {
		display($CHANGE, "Your new password needs to be longer than 6 characters!", null);
	} 
	
	#Password length can't be too long. If it is, BCRYPT does some really funkey stuff.
	if(strlen($PASSWORD1) > 70) {
		display($CHANGE, "I know you like security, but you'll break things if your new password is that long.", null);
	} 
	
	#Connect to database
	$options = ['cost' => 12];
	$stmt = $conn->prepare("UPDATE ACCOUNTS SET PASSWORD = :password WHERE ID = :id");
	$stmt->execute(array('password' => password_hash($PASSWORD1, PASSWORD_BCRYPT, $options),
						 'id' => $_SESSION["TEMP_ID"]));
	
	#Destroy the session
	session_destroy();
	
	#Return to login page
	display($LOGIN, null, "You changed your password!");
}

#If we are logged in, redirect to the editor.
if(isset($_SESSION['USERNAME']) && $_SESSION['VALID']){
	header("location: /edit.php");
	die();
}

#Display the log in by default.
display($LOGIN, null, null);
