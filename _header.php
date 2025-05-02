<?php
include "./engine/_include/function.php";
if (!isset($_SESSION["user_idx"])) {
  Util::goLocation("/login.php");
}
$currentPage = basename($_SERVER['PHP_SELF']);
$user = new User();
$myInfo = $user->getUserInfo($_SESSION["user_idx"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedules</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
</head>
<body class="bg-body-tertiary">
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Schedules</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>" aria-current="page" href="/">Home</a>
        </li>
        <?php if ($myInfo["role"] == 1): ?>
        <li class="nav-item">
          <a class="nav-link <?= $currentPage == 'stat.php' ? 'active' : '' ?>" href="/stat.php">Stats</a>
        </li>
        <?php endif; ?>
      </ul>
      <form class="d-flex" role="user">
        <span class="navbar-text mx-2">
          <span class="badge text-bg-dark"><?= $myInfo["role"] == 1 ? "관리자" : "일반사용자" ?></span>
          <?=$myInfo["username"]?>
        </span>
        <button id="logoutBtn" class="btn btn-dark" type="button">Logout</button>
      </form>
    </div>
  </div>
</nav>