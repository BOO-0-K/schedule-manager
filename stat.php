<?php 
include '_header.php'; 
if ($myInfo["role"] != 1) {
    Util::goAlertLocation("/", "권한이 없습니다.");
}
?>
<div class="container">
    <h2>월별 일정 통계</h2>
    <label for="monthSelect">월 선택:</label>
    <input type="month" id="monthSelect" value="<?= date('Y-m') ?>">
    <div style="max-width: 600px;">
        <canvas id="myChart" width="400" height="300"></canvas>
    </div>
</div>
<?php include '_footer.php'; ?>