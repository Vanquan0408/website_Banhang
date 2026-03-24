<?php
    if(isset($_GET['dangxuat'])&&$_GET['dangxuat']==1){
        unset($_SESSION['dangnhap']);
        header('Location:login.php');
    }
?>
<header class="admin-topbar" role="banner">
    <div class="admin-topbar-left">
        <div class="admin-topbar-search" role="search" aria-label="Tìm kiếm trong trang">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path fill="currentColor" d="M10.5 3a7.5 7.5 0 1 1 4.62 13.41l3.23 3.22a1 1 0 0 1-1.42 1.42l-3.23-3.23A7.5 7.5 0 0 1 10.5 3Zm0 2a5.5 5.5 0 1 0 0 11a5.5 5.5 0 0 0 0-11Z"/>
            </svg>
            <input id="adminTopbarSearch" type="search" placeholder="Tìm kiếm nhanh..." autocomplete="off" />
        </div>
    </div>

    <div class="admin-topbar-right">
        <div class="admin-user-menu">
            <button type="button" class="admin-user-trigger" aria-haspopup="menu" aria-expanded="false">
                <span class="admin-user-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                        <path fill="currentColor" d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2c-4.1 0-7.5 2.2-7.5 5a1 1 0 1 0 2 0c0-1.5 2.5-3 5.5-3s5.5 1.5 5.5 3a1 1 0 1 0 2 0c0-2.8-3.4-5-7.5-5Z"/>
                    </svg>
                </span>
                <span class="admin-user-name">
                    <?php
                    if(isset($_SESSION['dangnhap'])){
                        echo htmlspecialchars($_SESSION['dangnhap']);
                    } else {
                        echo 'Admin';
                    }
                    ?>
                </span>
                <span class="admin-user-caret" aria-hidden="true">
                    <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                        <path fill="currentColor" d="M7 10a1 1 0 0 1 1.7-.7L12 12.6l3.3-3.3A1 1 0 1 1 16.7 10.7l-4 4a1 1 0 0 1-1.4 0l-4-4A1 1 0 0 1 7 10Z"/>
                    </svg>
                </span>
            </button>

            <div class="admin-user-dropdown" role="menu" aria-label="Tài khoản">
                <a class="admin-user-item admin-user-item--primary" role="menuitem" href="index.php?action=taikhoan&query=thongtin">Thông tin tài khoản</a>
                <a class="admin-user-item admincp_logout" role="menuitem" href="index.php?dangxuat=1">Đăng xuất</a>
            </div>
        </div>
    </div>
</header>

<script>
(function(){
    var input = document.getElementById('adminTopbarSearch');
    if (input) {
        input.addEventListener('input', function(){
            var q = (this.value || '').toString().trim().toLowerCase();
            var tables = document.querySelectorAll('table.styled-table');
            tables.forEach(function(table){
                var bodyRows = [];
                if (table.tBodies && table.tBodies.length) {
                    bodyRows = Array.from(table.tBodies[0].rows || []);
                } else {
                    bodyRows = Array.from(table.querySelectorAll('tr')).slice(1);
                }
                bodyRows.forEach(function(row){
                    var text = (row.textContent || '').toLowerCase();
                    row.style.display = (q === '' || text.indexOf(q) !== -1) ? '' : 'none';
                });
            });
        });
    }

    var trigger = document.querySelector('.admin-user-trigger');
    var dropdown = document.querySelector('.admin-user-dropdown');
    if (trigger && dropdown) {
        var close = function(){
            dropdown.classList.remove('is-open');
            trigger.setAttribute('aria-expanded', 'false');
        };

        trigger.addEventListener('click', function(e){
            e.preventDefault();
            e.stopPropagation();
            var isOpen = dropdown.classList.toggle('is-open');
            trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        dropdown.addEventListener('click', function(e){ e.stopPropagation(); });
        document.addEventListener('click', close);
        document.addEventListener('keydown', function(e){ if (e.key === 'Escape') close(); });
    }
})();
</script>