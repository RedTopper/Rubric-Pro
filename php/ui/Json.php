<?php
namespace RubricPro\ui;

abstract class Json {
	private $json = [];

	public function __construct() {
		$this->addJson("type", get_class($this));
	}

	public final function sendJson() {
		header("Content-Type: application/json");
		$this->compile();
		echo json_encode($this->json);
		die();
	}

	protected final function addJson($key, $data) {
		$this->json[$key] = $data;
	}

	public final function getJson() {
		$this->compile();
		return $this->json;
	}

	protected abstract function compile();
}