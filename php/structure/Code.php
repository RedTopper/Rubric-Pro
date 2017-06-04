<?php
namespace RubricPro\structure;

class Code {
	const ERROR = "error";
	const SUCCESS = "success";
	const CHANGE = "change";

	private $message;
	private $type;
	private $code;

	public function __construct($type, $code, $message) {
		$this->message = $message;
		$this->type = $type;
		$this->code = $code;
	}

	public function getMessage() {
		return $this->message;
	}

	public function getCode() {
		return $this->code;
	}
}