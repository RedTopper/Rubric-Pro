<?php
namespace RubricPro;

use RubricPro\flow\user\User;
use \PDO;
use \PDOException;
use RubricPro\ui\info\Status;

require_once "../php/load.php";
$servername = "localhost";
$dbname = "rubric";
$username = "rubric";
$password = "QuelGNMLKRk956oX";
try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); #login
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); #Enable errors
} catch(PDOException $e) {
	new Status(Status::ERROR_DB(), "Could not connect to the database server!", "Sorry about that :(");
}


$user = new User($conn);
$user->fromPasswordChange("aaron", "AaronJWalter6", "AaronJWalter6");
$user->sendJson();
