<?php
namespace RubricPro\ui\info;

use RubricPro\ui\Json;

class Paragraph extends Json {
	private $body = [];

	public function __construct($body) {
		parent::__construct();
		array_push($this->body, $body);
	}

	public function addParagraph($body) {
		array_push($this->body, $body);
	}

	protected function compile() {
		$this->addJson("body",  $this->body);
	}
}