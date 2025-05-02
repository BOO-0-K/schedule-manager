<?php
include "./engine/_include/function.php";
if (isset($_SESSION["user_idx"])) {
    Util::goLocation("/");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<body>
    <div class="container-fluid row justify-content-center align-items-center">
        <form id="loginForm" class="w-50">
            <div class="mb-3 text-center">
                <h1>Login</h1>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password">
            </div>
            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-dark">Submit</button>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script>
        $(function(){
            $('#loginForm').on("submit", function(event) {
                event.preventDefault();
                $.ajax({
                    type: "post",
                    url: "/engine/login.php",
                    data: {
                        username: $("#username").val(),
                        password: $("#password").val(),
                    },
                    success: function(resp) {
                        if (resp.code == 0) {
                            window.location.href = "/";
                        } else {
                            alert("잘못된 로그인 정보입니다.");
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>