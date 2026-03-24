<?php
session_start();
if(!isset($_SESSION['dangnhap'])){
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admincp</title>
    <link rel="stylesheet" href="css/styleadmincp.css?v=20260324">
</head>
<body class="admin-body">
    <?php
    include("config/config.php");
    ?>

    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="admin-brand">
                <div class="admin-brand-mark">AD</div>
                <div class="admin-brand-text">
                    <div class="admin-brand-title">Admincp</div>
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
