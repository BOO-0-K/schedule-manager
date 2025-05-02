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
$scheduleInfo = $schedule->getScheduleDetail($_PICK["idx"]);
$participants = $schedule->getScheduleParticipants($_PICK["idx"]);
$scheduleIdx = $schedule->createSchedule($userIdx, $scheduleInfo["title"], $scheduleInfo["location"], $scheduleInfo["type"], $scheduleInfo["start_time"], $scheduleInfo["end_time"]);
foreach ($participants as $participantIdx) {
    $schedule->createParticipant($scheduleIdx, $participantIdx);
}

API::Result(0);