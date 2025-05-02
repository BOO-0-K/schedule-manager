<?php
session_start();
require "config.php";
require ABSOLUTE_PATH."engine/_include/errors.php";
if(USE_COMPOSER) {
    require ABSOLUTE_PATH."vendor/autoload.php";
}

class AutoLoader {
    public function __construct() {
        spl_autoload_register( array($this, 'load') );
    }
    function load( $className ) {
        require_once ABSOLUTE_PATH."engine/_include/classes/class.".$className.".php";
    }
}

$autoload = new AutoLoader();
?>
