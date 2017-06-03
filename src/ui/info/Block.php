<?php
namespace RubricPro\ui\info;

use RubricPro\ui\Json;
use \Exception;

class Block extends Json {
	protected $body = null;
	protected $title = null;

	public function setBody($body) {
		$this->body = $body;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	protected function compile() {
		throw new Exception("Unimplemented");
	}
}