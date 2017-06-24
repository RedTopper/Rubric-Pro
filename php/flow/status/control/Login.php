<?php
namespace RubricPro\flow\status\control;

class Login extends Code {

	private $username;

	public function __construct($type, $httpCode, $title, $message, $hint, $username) {
		parent::__construct($type, $httpCode, $title, $message, $hint);
		$this->username = $username;
	}

	protected function compile() {
		parent::compile();
		$this->addJson("username", $this->username);
	}
}