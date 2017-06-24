<?php
namespace RubricPro\flow\status\message;

use RubricPro\flow\status\control\Login;

class Change {

	const CHANGE = "change";

	public static final function FIRST_TIME($username) {
		(new Login(
		self::CHANGE,
		300,
		"First Time Login",
		ucfirst($username) . ", since this is your first time logging in, you'll need to change your password.",
		"Please change your password!",
		$username))->sendJson();
	}

	public static final function REQUIRED($username) {
		(new Login(
		self::CHANGE,
		300,
		"Password Change Required",
		ucfirst($username) . ", your administrator requires you to change your password before logging in.",
		"Please change your password!",
		$username))->sendJson();
	}
}