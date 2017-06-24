<?php
namespace RubricPro\flow\status\message;

use RubricPro\flow\status\control\Login;

class Success {

	const SUCCESS = "success";
	const LOGIN = "login";

	public static final function PASSWORD_CHANGED($username) {
		(new Login(
		self::SUCCESS,
		202,
		"Password Changed!",
		"$username, your password has been changed successfully!",
		"You may now log in.",
		$username))->sendJson();
	}
	public static final function LOGIN($username) {
		(new Login(
		self::LOGIN,
		200,
		"Login Successful!",
		"$username, you are now logged in!",
		"Have fun, $username!",
		$username))->sendJson();
	}
	public static final function LOGOUT($username) {
		(new Login(
		self::SUCCESS,
		200,
		"Logout Successful!",
		"$username, you are now logged out!",
		"For security you can close this window.",
		$username))->sendJson();
	}
}