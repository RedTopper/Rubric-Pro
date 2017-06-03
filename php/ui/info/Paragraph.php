<?php
namespace RubricPro\ui\info;

use RubricPro\ui\Json;

class Paragraph extends Json {
	private $body = [];

	public function __construct($para) {
		parent::__construct();
		array_push($this->body, $para);
	}

	public function addParagraph($para) {
		array_push($this->body, $para);
	}

	protected function compile() {
		$this->addJson("body",  $this->body);
	}
}