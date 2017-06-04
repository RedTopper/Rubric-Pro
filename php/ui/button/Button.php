<?php
namespace RubricPro\ui\button;

use RubricPro\ui\info\Paragraph;

class Button extends Paragraph {
	private $uri;
	private $title;
	private $data = [];

	public function __construct($title, $body, $uri) {
		parent::__construct($body);
		$this->title = $title;
		$this->uri = $uri;
	}

	public function addData($key, $value) {
		$this->data[$key] = $value;
	}

	protected function compile() {
		parent::compile();
		$this->addJson("title", $this->title);
		$this->addJson("uri", $this->uri);
		$this->addJson("data", $this->data);
	}
}