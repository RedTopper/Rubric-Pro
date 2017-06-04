<?php
namespace ui\info;

use RubricPro\ui\Json;
use structure\Code;

class Status extends Json {

	public static final function ERR_DB() {return new Code("Database Connection Failed", 504);}
	public static final function ERR_SERVER() {return new Code("Internal Server Error", 500);}
	public static final function ERR_CLIENT() {return new Code("Request Error", 400);}
	public static final function ERR_TIMEOUT() {return new Code("Session timed out", 400);}
	public static final function ERR_AUTH() {return new Code("Permission Denied", 403);}
	public static final function ERR_MISSING() {return new Code("Not Found", 404);}

	private $error;
	private $message;
	private $hint;

	/**
	 * Status constructor. Immediately kills the PHP program once constructed.
	 *
	 * @param Code $error - An error code, usually represents some HTTP code.
	 * @param $message - A message to deliver to the user.
	 * @param $hint - A small hint in case the message doesn't explain enough.
	 */
	public function __construct(Code $error, $message, $hint) {
		parent::__construct();
		$this->error = $error;
		$this->message = $message;
		$this->hint = $hint;
		$this->sendJson();
	}

	protected function compile() {
		http_response_code($this->error->getCode());
		$this->addJson("error", $this->error->getMessage());
		$this->addJson("code", $this->error->getCode());
		$this->addJson("message", $this->message);
		$this->addJson("hint", $this->hint);
	}
}