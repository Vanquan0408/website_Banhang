<?php
session_start();
if(!isset($_SESSION['dangnhap'])){
    header('Location: login.php');
    exit();
}

// Per-tab login enforcement:
// - After a successful login, we allow ONE bootstrap render to set sessionStorage.
// - On subsequent visits, if the tab was closed (sessionStorage cleared), the browser will auto-logout and redirect to login.
$__adminJustLoggedIn = isset($_SESSION['admin_just_logged_in']);
if ($__adminJustLoggedIn) {
    unset($_SESSION['admin_just_logged_in']);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Website Bán Hàng</title>
    <link rel="stylesheet" href="css/styleadmincp.css?v=20260324">

    <style>
        /* Prevent admin UI flash before tab-check runs */
        html{visibility:hidden;}
    </style>
    <script>
        (function(){
            var KEY = 'admincp_tab_alive_v1';
            var justLoggedIn = <?php echo $__adminJustLoggedIn ? 'true' : 'false'; ?>;

            function redirectToLogin(){
                window.location.replace('login.php');
            }

            try {
                if (justLoggedIn) {
                    sessionStorage.setItem(KEY, '1');
                    document.documentElement.style.visibility = 'visible';
                    return;
                }

                if (!sessionStorage.getItem(KEY)) {
                    redirectToLogin();
                    return;
                }

                document.documentElement.style.visibility = 'visible';
            } catch (e) {
                // If sessionStorage is blocked, fail closed (force login)
                redirectToLogin();
            }
        })();
    </script>
</head>
<body class="admin-body">
    <?php
    include("config/config.php");
    ?>

    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="admin-brand">
                <div class="admin-brand-text">
                    <div class="admin-brand-title">ADMIN</div>
                    <div class="admin-brand-sub">Quản lý hệ thống</div>
                </div>
            </div>

            <?php include("modules/menu.php"); ?>
        </aside>

        <div class="admin-workspace">
            <?php include("modules/header.php"); ?>

            <main class="admin-content">
                <?php
                include("modules/main.php");
                ?>
            </main>
        </div>
    </div>
</body>
</html>

