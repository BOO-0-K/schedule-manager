<?php
include "../engine/_include/function.php";
$db = new DB;
$db->connect();

$_PICK["username"] = $db->clean($_POST["username"]);
$_PICK["password"] = $db->clean($_POST["password"]);

Util::checkRequireField($_PICK, [
    ["username", "아이디를 입력해주세요."],
    ["password", "비밀번호를 입력해주세요."]
]);

$user = new User();
$userInfo = $user->login($_PICK["username"], $_PICK["password"]);
if (!empty($userInfo)) {
    $_SESSION["user_idx"] = $userInfo["idx"];
    API::Result(0);
}

API::Result(-3);