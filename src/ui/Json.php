<?php
namespace RubricPro\ui;

abstract class Json {
	private $data = [];

	public final function __construct() {
		$this->addData("type", get_class($this));
	}

	public final function sendJson() {
		header("Content-Type: application/json");
		$this->compile();
		echo json_encode($this->data);
		die();
	}

	protected final function addData($key, $data) {
		$this->data[$key] = $data;
	}

	public final function getData() {
		$this->compile();
		return $this->data;
	}

	protected abstract function compile();
}