<?php
namespace RubricPro\ui\info;

use RubricPro\ui\Json;

class Title extends Json {
	protected $title;

	public function __construct($title) {
		parent::__construct();
		$this->title = $title;
	}

	protected function compile() {
		$this->addJson("title", $this->title);
	}
}