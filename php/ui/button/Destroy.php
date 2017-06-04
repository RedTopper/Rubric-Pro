<?php
namespace RubricPro\ui\button;

class Destroy extends Button {
	private $danger;

	public function __construct($title, $body, $uri, $danger = true) {
		parent::__construct($title, $body, $uri);
		$this->danger = $danger;
	}

	public function compile() {
		parent::compile();
		$this->addJson("danger", ($this->danger === false ? false : true));
	}
}