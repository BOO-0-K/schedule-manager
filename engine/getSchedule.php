<?php
include "../engine/_include/function.php";
$db = new DB;
$db->connect();

$userIdx = $_SESSION["user_idx"];
if (empty($userIdx)) {
    API::Result("-3");
}

$_PICK["idx"] = $db->clean($_POST["idx"]);

$schedule = new Schedule();
$participants = $schedule->getScheduleParticipants($_PICK["idx"]);

API::Result(0, $participants);