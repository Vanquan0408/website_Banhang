<?php
session_start();
include('config/config.php');

if(isset($_POST['dangnhap'])){
    $taikhoan = $_POST['username'];
    $matkhau = md5($_POST['password']);
    $stmt = $mysqli->prepare("SELECT * FROM admin WHERE username=? AND password=? LIMIT 1");
    $stmt->bind_param("ss", $taikhoan, $matkhau);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;

    if($count > 0){
        $_SESSION['dangnhap'] = $taikhoan;
        header("Location: index.php");
        exit();
    } else {
        echo '<script>alert("Tài khoản hoặc mật khẩu sai!"); window.location.href="login.php";</script>';
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Đăng nhập Admin</title>
        <link rel="stylesheet" href="css/styleadmincp.css?v=20260324">
    </head>
<body class="admin-login-page">
    <div class="admin-login-shell">
        <div class="admin-login-card">
            <div class="admin-login-title">Đăng nhập Admin</div>

            <form action="" autocomplete="off" method="POST" class="admin-login-form">
                <div class="admin-field">
                    <label for="admin_username">Tài khoản</label>
                    <div class="admin-input">
                        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path fill="currentColor" d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2c-4.1 0-7.5 2.2-7.5 5a1 1 0 1 0 2 0c0-1.5 2.5-3 5.5-3s5.5 1.5 5.5 3a1 1 0 1 0 2 0c0-2.8-3.4-5-7.5-5Z"/>
                        </svg>
                        <input id="admin_username" type="text" name="username" placeholder="Nhập tài khoản" autocomplete="username" required>
                    </div>
                </div>

                <div class="admin-field">
                    <label for="admin_password">Mật khẩu</label>
                    <div class="admin-input has-toggle">
                        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path fill="currentColor" d="M12 1a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5Zm-3 8V6a3 3 0 0 1 6 0v3H9Z"/>
                        </svg>
                        <input id="admin_password" type="password" name="password" placeholder="Nhập mật khẩu" autocomplete="current-password" required>
                        <button type="button" class="admin-toggle" data-target="admin_password" aria-label="Ẩn/hiện mật khẩu" aria-pressed="false">
                            <svg class="icon-eye" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 5c5.5 0 9.7 5.2 9.9 5.4a1 1 0 0 1 0 1.2C21.7 11.8 17.5 17 12 17S2.3 11.8 2.1 11.6a1 1 0 0 1 0-1.2C2.3 10.2 6.5 5 12 5Zm0 10a4 4 0 1 0-4-4a4 4 0 0 0 4 4Z"/></svg>
                            <svg class="icon-eye-off" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M3.3 2.3a1 1 0 0 1 1.4 0l16 16a1 1 0 1 1-1.4 1.4l-2.1-2.1A10.9 10.9 0 0 1 12 19C6.5 19 2.3 13.8 2.1 13.6a1 1 0 0 1 0-1.2A18.6 18.6 0 0 1 6.2 8.3L3.3 5.4a1 1 0 0 1 0-1.4ZM12 7c-.5 0-1 .05-1.5.14l2.1 2.1A4 4 0 0 1 15.7 12l2.2 2.2a18.2 18.2 0 0 0 2-2.6C18.7 10.2 15.5 7 12 7Zm-6.1 4.1a18.2 18.2 0 0 0-1.8 2.5C5.3 15 8.5 18 12 18c1.4 0 2.7-.4 3.8-1l-1.6-1.6A4 4 0 0 1 8.6 9.8L5.9 7.1Z"/></svg>
                        </button>
                    </div>
                </div>

                <button class="admin-submit" type="submit" name="dangnhap" value="1">Đăng nhập</button>

                <div class="admin-login-footer">
                    <a href="../index.php">← Về trang bán hàng</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            var toggle = document.querySelector('.admin-toggle');
            if (!toggle) return;
            toggle.addEventListener('click', function () {
                var targetId = toggle.getAttribute('data-target');
                if (!targetId) return;
                var input = document.getElementById(targetId);
                if (!input) return;
                var isPassword = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPassword ? 'text' : 'password');
                toggle.classList.toggle('is-on', isPassword);
                toggle.setAttribute('aria-pressed', isPassword ? 'true' : 'false');
            });
        })();
    </script>
</body>
</html>