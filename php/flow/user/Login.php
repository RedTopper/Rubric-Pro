<?php
namespace RubricPro\flow\user;

use RubricPro\structure\Code;
use RubricPro\ui\info\Status;

class Login extends Status {

	#success messages
	public static final function SUCCESS_CHANGED()
	{return new Code(Code::SUCCESS, 202, "Password Changed!");}

	public static final function SUCCESS_LOGIN()
	{return new Code(Code::SUCCESS, 200, "Login Successful!");}

	#change messages
	public static final function CHANGE_FIRST_TIME()
	{return new Code(Code::CHANGE, 300, "First Time Login");}

	public static final function CHANGE_REQUIRED()
	{return new Code(Code::CHANGE, 300, "Password Change Required");}

	#error messages
	public static final function ERROR_NO_CHANGE()
	{return new Code(Code::ERROR, 403, "No Change Required");}

	public static final function ERROR_PASSWORD_CHANGE_MISMATCH()
	{return new Code(Code::ERROR, 400, "Password Mismatch");}

	public static final function ERROR_USERNAME_PASSWORD_WRONG()
	{return new Code(Code::ERROR, 400, "Incorrect Username");}

	public static final function ERROR_PASSWORD_SHORT()
	{return new Code(Code::ERROR, 400, "Password Too Short");}

	public static final function ERROR_PASSWORD_LONG()
	{return new Code(Code::ERROR, 400, "Password Too Long");}

	public static final function ERROR_USERNAME_EXISTS()
	{return new Code(Code::ERROR, 400, "Account Already Exists");}

	private $username;

	#creation
	public function __construct(Code $error, $message, $hint, $username) {
		parent::__construct($error, $message, $hint);
		$this->username = $username;
		$this->destroy2();
	}

	protected function destroy() {}
	protected function destroy2() {
		$this->sendJson();
	}

	protected function compile() {
		parent::compile();
		$this->addJson("username", $this->username);
	}
}