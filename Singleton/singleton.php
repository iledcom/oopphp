<?php

class Singleton {
	public $data;
	private static $instance = null;

	private function __construct() {
		$this->data = rand();
	}

	public static function getInstance() {
		if(is_null(self::$instance)) self::$instance = new self;

		return self::$instance;
	}

	private function __clone() {}
	private function __wakeup() {}
} 