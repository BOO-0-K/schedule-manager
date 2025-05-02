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
$scheduleList = $schedule->getScheduleList($_PICK["start"], $_PICK["end"]);

$events = array();
foreach ($scheduleList as $scheduleDetail) {
    $user = new User();
    $userInfo = $user->getUserInfo($scheduleDetail["user_idx"]);
    $isAdmin = $user->isAdmin($userIdx);
    $isMySchedule = $schedule->isMySchedule($userIdx, $scheduleDetail["idx"]);
    $isRole = $isAdmin || $isMySchedule;
    $colors = [
        1 => "tomato",
        2 => "dodgerblue",
        3 => "mediumseagreen",
        4 => "violet",
    ];
    $event = [
        "idx" => $scheduleDetail["idx"],
        "username" => $userInfo["username"],
        "title" => $scheduleDetail["title"],
        "location" => $scheduleDetail["location"],
        "type" => $scheduleDetail["type"],
        "start" => $scheduleDetail["start_time"],
        "end" => $scheduleDetail["end_time"],
        "color" => $colors[$scheduleDetail["type"]],
        "role" => $isRole,
    ];
    array_push($events, $event);
}

API::Result(0, $events);