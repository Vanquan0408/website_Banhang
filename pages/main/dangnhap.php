
<?php
    $login_message = '';
    $login_redirect = '';

    if(isset($_POST['dangnhap'])){
        $email = trim($_POST['email'] ?? '');
        $matkhau = (string)($_POST['password'] ?? '');

        // Lấy dữ liệu người dùng theo email
        $stmt = $mysqli->prepare('SELECT * FROM table_dangky WHERE email=? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $query = $stmt->get_result();
        $count = $query ? $query->num_rows : 0;

        if($count > 0){
            $row_data = $query->fetch_assoc();

            // So sánh mật khẩu người dùng nhập với mật khẩu đã mã hóa
            if($row_data && password_verify($matkhau, $row_data['matkhau'])){
                $_SESSION['dangky'] = $row_data['tenkhachhang'];
                $_SESSION['id_khachhang'] = $row_data['id_dangky'];

                $login_message = '<div class="alert alert-success">Đăng nhập thành công. Đang chuyển trang...</div>';
                $login_redirect = 'index.php';
            } else {
                $login_message = '<div class="alert alert-error">Mật khẩu không đúng. Vui lòng thử lại.</div>';
            }
        } else {
            $login_message = '<div class="alert alert-error">Tài khoản không tồn tại.</div>';
        }

        if (isset($stmt) && $stmt) { $stmt->close(); }
    }
?>

<div class="maincontent">
    <div class="content">
        <div class="content_right">
            <div class="auth-card">
                <div class="auth-head">
                    <div class="auth-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2c-4.1 0-7.5 2.2-7.5 5a1 1 0 1 0 2 0c0-1.5 2.5-3 5.5-3s5.5 1.5 5.5 3a1 1 0 1 0 2 0c0-2.8-3.4-5-7.5-5Z"/></svg>
                    </div>
                    <div>
                        <div class="auth-title">Đăng nhập</div>
                        <div class="auth-subtitle">Chào mừng bạn quay lại. Đăng nhập để mua hàng nhanh hơn.</div>
                    </div>
                </div>

                <?php
                    if (!empty($login_message)) {
                        echo $login_message;
                    }
                ?>

                <form action="" autocomplete="on" method="POST" class="auth-form">
                    <div class="auth-grid is-single">
                        <div class="field">
                            <label for="login_email">Email</label>
                            <div class="input-shell">
                                <span class="input-leading" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M20 5H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2Zm0 2l-8 5L4 7h16Zm0 10H4V9l8 5l8-5v8Z"/></svg>
                                </span>
                                <input id="login_email" type="email" name="email" placeholder="email@example.com" autocomplete="email" required>
                            </div>
                        </div>

                        <div class="field">
                            <label for="login_password">Mật khẩu</label>
                            <div class="input-shell has-toggle">
                                <span class="input-leading" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 1a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5Zm-3 8V6a3 3 0 0 1 6 0v3H9Z"/></svg>
                                </span>
                                <input id="login_password" type="password" name="password" placeholder="Mật khẩu..." autocomplete="current-password" required>
                                <button type="button" class="input-trailing js-toggle-password" data-target="login_password" aria-label="Ẩn/hiện mật khẩu" aria-pressed="false">
                                    <svg class="icon-eye" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 5c5.5 0 9.7 5.2 9.9 5.4a1 1 0 0 1 0 1.2C21.7 11.8 17.5 17 12 17S2.3 11.8 2.1 11.6a1 1 0 0 1 0-1.2C2.3 10.2 6.5 5 12 5Zm0 10a4 4 0 1 0-4-4a4 4 0 0 0 4 4Z"/></svg>
                                    <svg class="icon-eye-off" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M3.3 2.3a1 1 0 0 1 1.4 0l16 16a1 1 0 1 1-1.4 1.4l-2.1-2.1A10.9 10.9 0 0 1 12 19C6.5 19 2.3 13.8 2.1 13.6a1 1 0 0 1 0-1.2A18.6 18.6 0 0 1 6.2 8.3L3.3 5.4a1 1 0 0 1 0-1.4ZM12 7c-.5 0-1 .05-1.5.14l2.1 2.1A4 4 0 0 1 15.7 12l2.2 2.2a18.2 18.2 0 0 0 2-2.6C18.7 10.2 15.5 7 12 7Zm-6.1 4.1a18.2 18.2 0 0 0-1.8 2.5C5.3 15 8.5 18 12 18c1.4 0 2.7-.4 3.8-1l-1.6-1.6A4 4 0 0 1 8.6 9.8L5.9 7.1Z"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="auth-actions">
                        <button class="btn-primary" type="submit" name="dangnhap" value="1">
                            Đăng nhập
                        </button>
                        <a class="btn-secondary" href="index.php?quanly=dangky">Đăng ký</a>
                    </div>

                    <div class="auth-footer">Thông tin đăng nhập được bảo mật. Không chia sẻ mật khẩu với bất kỳ ai.</div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($login_redirect)) { ?>
<script>
    setTimeout(function(){
        window.location.href = <?php echo json_encode($login_redirect); ?>;
    }, 900);
</script>
<?php } ?>