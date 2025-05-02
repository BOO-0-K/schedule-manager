<?php
include "../engine/_include/function.php";
$db = new DB;
$db->connect();

$userIdx = $_SESSION["user_idx"];
if (empty($userIdx)) {
    API::Result("-3");
}

$user = new User();
$isAdmin = $user->isAdmin($userIdx);

$_PICK["idx"] = $db->clean($_POST["idx"]);

$schedule = new Schedule();
$isMySchedule = $schedule->isMySchedule($userIdx, $_PICK["idx"]);

if (!$isMySchedule && !$isAdmin) {
    API::Result(-3);
}

$schedule->deleteSchedule($_PICK["idx"]);

API::Result(0);