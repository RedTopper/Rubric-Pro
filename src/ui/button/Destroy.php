<?php
namespace RubricPro\ui\button;

class Destroy extends Button {
	private $danger = true;

	public function setDanger($danger) {
		$this->danger = $danger;
	}

	public function compile() {
		parent::compile();
		$this->addData("danger", ($this->danger === false ? false : true));
	}
}