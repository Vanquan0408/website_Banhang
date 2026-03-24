<?php
    if(isset($_GET['dangxuat'])&&$_GET['dangxuat']==1){
        unset($_SESSION['dangnhap']);
        header('Location:login.php');
    }
?>
<header class="admin-topbar" role="banner">
    <div class="admin-topbar-left">
        <div class="admin-topbar-title">Bảng điều khiển</div>
        <div class="admin-topbar-sub">Quản trị bán hàng</div>
    </div>

    <div class="admin-topbar-right">
        <div class="admin-user">
            <div class="admin-user-name">
                <?php
                if(isset($_SESSION['dangnhap'])){
                    echo htmlspecialchars($_SESSION['dangnhap']);
                } else {
                    echo 'Admin';
                }
                ?>
            </div>
        </div>
        <a class="admincp_logout" href="index.php?dangxuat=1">Đăng xuất</a>
    </div>
</header>