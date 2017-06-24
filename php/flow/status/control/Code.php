<?php
namespace RubricPro\flow\status\control;

use RubricPro\ui\Json;

class Code extends Json {

	private $type;
	private $httpCode;
	private $title;
	private $message;
	private $hint;

	/**
	 * Code constructor.
	 *
	 * @param $type string - Should be one of the strings in this class.
	 * @param $httpCode int - An http error code to return.
	 * @param $title string - Title of the message. Should be short.
	 * @param $message string - The actual message.
	 * @param $hint string - A small hint for the user.
	 */
	public function __construct($type, $httpCode, $title, $message, $hint) {
		parent::__construct();
		$this->title = $title;
		$this->type = $type;
		$this->httpCode = $httpCode;
		$this->message = $message;
		$this->hint = $hint;
	}

	protected function compile() {
		http_response_code($this->httpCode);
		$this->addJson("type", $this->type);
		$this->addJson("httpCode", $this->httpCode);
		$this->addJson("title", $this->title);
		$this->addJson("message", $this->message);
		$this->addJson("hint", $this->hint);
	}
}