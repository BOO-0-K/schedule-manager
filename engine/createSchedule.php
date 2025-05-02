<?php
include "../engine/_include/function.php";
$db = new DB;
$db->connect();

$userIdx = $_SESSION["user_idx"];
if (empty($userIdx)) {
    API::Result("-3");
}

$_PICK["title"] = $db->clean($_POST["title"]);
$_PICK["location"] = $db->clean($_POST["location"]);
$_PICK["type"] = $db->clean($_POST["type"]);
$_PICK["start_date"] = $db->clean($_POST["start_date"]);
$_PICK["start_time"] = $db->clean($_POST["start_time"]);
$_PICK["end_date"] = $db->clean($_POST["end_date"]);
$_PICK["end_time"] = $db->clean($_POST["end_time"]);
$_PICK["participants"] = stripslashes($db->clean($_POST["participants"]));

Util::checkRequireField($_PICK, [
    ["title", "제목을 입력해주세요."],
    ["location", "위치를 입력해주세요."],
    ["type", "유형을 입력해주세요."],
    ["start_date", "시작 날짜를 입력해주세요."],
    ["start_time", "시작 시간을 입력해주세요."],
    ["end_date", "종료 날짜를 입력해주세요."],
    ["end_time", "종료 시간을 입력해주세요."],
]);

$startTime = new DateTime($_PICK["start_date"] . " " . $_PICK["start_time"]);
$endTime = new DateTime($_PICK["end_date"] . " " . $_PICK["end_time"]);
if ($endTime < $startTime) {
    API::Result(-5);
}

$participants = json_decode($_PICK["participants"], true);
if (empty($participants)) {
    API::Result(-2, "참여자를 입력해주세요.");
}

$schedule = new Schedule();
$scheduleIdx = $schedule->createSchedule($userIdx, $_PICK["title"], $_PICK["location"], $_PICK["type"], $startTime->format("Y-m-d H:i:s"), $endTime->format("Y-m-d H:i:s"));
foreach ($participants as $participantIdx) {
    $schedule->createParticipant($scheduleIdx, $participantIdx);
}

API::Result(0);