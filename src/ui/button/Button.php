<?php
namespace RubricPro\ui\button;

use RubricPro\ui\info\Block;

class Button extends Block {
	private $uri = null;
	private $meta = [];

	public function  setUri($uri) {
		$this->uri = $uri;
	}

	public function addMetadata($key, $value) {
		$meta[$key] = $value;
	}

	protected function compile() {
		$this->addData("title", ($this->title === null ? "Error" : $this->title));
		$this->addData("body",  $this->body);
		$this->addData("uri", $this->uri);
		$this->addData("meta", $this->meta);
	}
}