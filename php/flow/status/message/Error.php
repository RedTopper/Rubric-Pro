<?php
namespace RubricPro\flow\status\message;

use RubricPro\flow\status\control\Code;
use RubricPro\flow\status\control\Login;
use RubricPro\flow\user\User;

class Error {

	const ERROR = "error";

	#general error messages
	public static final function DB() {
		(new Code(
		self::ERROR,
		504,
		"Database Connection Failed",
		"An internal server error occurred when connecting to the database",
		"Please try again later!"))->sendJson();
	}

	public static final function SERVER() {
		(new Code(
		self::ERROR,
		500,
		"Internal Server Error",
		"An unknown error occurred when performing that action.",
		"Sorry about that!"))->sendJson();
	}

	public static final function CLIENT() {
		(new Code(
		self::ERROR,
		400,
		"Request Error",
		"Your client sent invalid data to the server.",
		"Please try again with a different input."))->sendJson();
	}

	public static final function TIMEOUT() {
		(new Code(
		self::ERROR,
		400,
		"Session timed out",
		"You have been automatically logged out due to inactivity",
		"Please return to the login page."))->sendJson();
	}

	public static final function AUTHENTICATION() {
		(new Code(
		self::ERROR,
		403,
		"Permission Denied",
		"You are not authorized to access this resource.",
		"Please check your privilege."))->sendJson();
	}

	public static final function NOT_FOUND() {
		(new Code(
		self::ERROR,
		404,
		"Not Found",
		"The requested resource was not found on the server.",
		"Check your url and try again."))->sendJson();
	}

	public static final function DELETED() {
		(new Code(
		self::ERROR,
		403,
		"Deleted",
		"Your account has been terminated.",
		"Sorry about that :("))->sendJson();
	}

	#login error messages
	public static final function NO_PASSWORD_CHANGE($username) {
		(new Login(
		self::ERROR,
		403,
		"No Change Required",
		ucfirst($username) . " does not need to change their password at this time.",
		"Nice try",
		$username))->sendJson();
	}

	public static final function PASSWORD_CHANGE_MISMATCH($username) {
		(new Login(
		self::ERROR,
		400,
		"Password Mismatch",
		"Your first password was different from the second password.",
		"Please try again!",
		$username))->sendJson();
	}
	public static final function USERNAME_PASSWORD_WRONG($username) {
		(new Login(
		self::ERROR,
		400,
		"Incorrect Username or Password",
		"The username or password you provided is incorrect.",
		"Please try again!",
		$username))->sendJson();
	}

	public static final function PASSWORD_SHORT($username) {
		(new Login(
		self::ERROR,
		400,
		"Password Too Short",
		ucfirst($username) . ", your password needs to be at least " . User::PASSWORD_MIN . " characters.",
		"Please create a longer password!",
		$username))->sendJson();
	}

	public static final function PASSWORD_LONG($username) {
		(new Login(
		self::ERROR,
		400,
		"Password Too Long",
		ucfirst($username) . ", your password is too powerful. It needs to be less than " . User::PASSWORD_MAX . " characters.",
		"Please create a shorter password!",
		$username))->sendJson();
	}

	public static final function USERNAME_EXISTS($username) {
		(new Login(
		self::ERROR,
		400,
		"Account Already Exists",
		ucfirst($username) . ", the account you are trying to create already exists within the database",
		"Please choose a different username.",
		$username))->sendJson();
	}
}