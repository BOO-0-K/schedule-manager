<?php
include "../engine/_include/function.php";

$_SESSION = array();
session_destroy();

API::Result(0);