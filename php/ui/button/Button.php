<?php
namespace RubricPro\ui\button;

use RubricPro\ui\info\Paragraph;

class Button extends Paragraph {
	private $uri;
	private $title;
	private $data = [];

	public function __construct($title, $para, $uri) {
		parent::__construct($para);
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