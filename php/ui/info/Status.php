<?php
namespace RubricPro\ui\info;

use RubricPro\ui\Json;
use RubricPro\structure\Code;

class Status extends Json {

	public static final function ERROR_DB()
	{return new Code(Code::ERROR, 504, "Database Connection Failed");}

	public static final function ERROR_SERVER()
	{return new Code(Code::ERROR, 500, "Internal Server Error");}

	public static final function ERROR_CLIENT()
	{return new Code(Code::ERROR, 400, "Request Error");}

	public static final function ERROR_TIMEOUT()
	{return new Code(Code::ERROR, 400, "Session timed out");}

	public static final function ERROR_AUTH()
	{return new Code(Code::ERROR, 403, "Permission Denied");}

	public static final function ERROR_MISSING()
	{return new Code(Code::ERROR, 404, "Not Found");}

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
		http_response_code($error->getCode());
		$this->error = $error;
		$this->message = $message;
		$this->hint = $hint;
		$this->destroy();
	}

	protected function destroy() {
		$this->sendJson();
	}

	protected function compile() {
		$this->addJson("error", $this->error->getMessage());
		$this->addJson("code", $this->error->getCode());
		$this->addJson("message", $this->message);
		$this->addJson("hint", $this->hint);
	}
}