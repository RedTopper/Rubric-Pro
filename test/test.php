<?php
namespace RubricPro;

use RubricPro\flow\user\User;
use \PDO;
use \PDOException;

require_once "../php/load.php";

try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); #login
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); #Enable errors
} catch(PDOException $e) {
	db_showError("Database Error", "An error occurred when connecting to the database.", "Sorry about that :(", 500);
}


%user = new User()
