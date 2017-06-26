<?php
namespace RubricPro\flow\status\control;

class Login extends Code {

	private $username;

	public function __construct($status, $http, $title, $message, $hint, $username) {
		parent::__construct($status, $http, $title, $message, $hint);
		$this->username = $username;
	}

	protected function compile() {
		parent::compile();
		$this->addJson("username", $this->username);
	}
}