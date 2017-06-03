<?php
namespace RubricPro\ui\info;

class Paragraph extends Block {
	protected function compile() {
		$this->addData("body", ($this->body === null ? "No Data" : $this->body));
	}
}