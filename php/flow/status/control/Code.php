<?php
namespace RubricPro\flow\status\control;

use RubricPro\ui\Json;

class Code extends Json {

	private $status;
	private $http;
	private $title;
	private $message;
	private $hint;

	/**
	 * Code constructor.
	 *
	 * @param $status string - Should be one of the strings in this class.
	 * @param $http int - An http error code to return.
	 * @param $title string - Title of the message. Should be short.
	 * @param $message string - The actual message.
	 * @param $hint string - A small hint for the user.
	 */
	public function __construct($status, $http, $title, $message, $hint) {
		parent::__construct();
		$this->title = $title;
		$this->status = $status;
		$this->http = $http;
		$this->message = $message;
		$this->hint = $hint;
	}

	protected function compile() {
		http_response_code($this->http);
		$this->addJson("status", $this->status);
		$this->addJson("http", $this->http);
		$this->addJson("title", $this->title);
		$this->addJson("message", $this->message);
		$this->addJson("hint", $this->hint);
	}
}