<?php
class Singleton {
	protected static $instance = null;

	private static $instances = array();
	function __construct() {}
	function __clone() {}
	public function __wakeup() {
		throw new Exception("Cannot unserialize singleton");
	}

	public static function getInstance() {
		if (!isset(static::$instance)) {
			static::$instance = new static;
		}
		return static::$instance;
	}
}
?>
