<?php
namespace RubricPro\ui\info;

class Subtitle extends Title {
	protected function compile() {
		$this->addData("title", ($this->title === null ? "Unknown" : $this->title));
	}
}