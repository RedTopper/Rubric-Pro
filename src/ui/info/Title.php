<?php
namespace RubricPro\ui\info;

class Title extends Block {
	protected function compile() {
		$this->addData("title", ($this->title === null ? "Untitled" : $this->title));
	}
}