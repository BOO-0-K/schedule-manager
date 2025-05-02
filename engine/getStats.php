<?php
include "../engine/_include/function.php";
$db = new DB;
$db->connect();

$userIdx = $_SESSION["user_idx"];
if (empty($userIdx)) {
    API::Result("-3");
}

$_PICK["start"] = $db->clean($_POST["start"]);
$_PICK["end"] = $db->clean($_POST["end"]);

$schedule = new Schedule();
$stats = $schedule->getStats($_PICK["start"], $_PICK["end"]);

API::Result(0, $stats);