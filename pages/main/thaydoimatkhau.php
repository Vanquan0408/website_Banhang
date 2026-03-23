<?php
if (isset($_POST['doimatkhau'])) {
    $taikhoan = $_POST['email'];
    $matkhau_cu = $_POST['password_cu'];
    $password_moi_raw = $_POST['password_moi'];
    $password_xacnhan_raw = $_POST['password_xacnhan'];

    if ($password_moi_raw !== $password_xacnhan_raw) {
        $message = '<div class="alert alert-error">Mật khẩu mới và xác nhận không trùng khớp.</div>';
    } elseif (
        strlen($password_moi_raw) < 9 || 
        !preg_match('/[A-Za-z]/', $password_moi_raw) || 
        !preg_match('/[0-9]/', $password_moi_raw)
    ) {
        $message = '<div class="alert alert-error">Mật khẩu mới phải có ít nhất 9 ký tự và bao gồm cả chữ cái và chữ số.</div>';
    } else {
        $sql = "SELECT * FROM table_dangky WHERE email='".$taikhoan."' LIMIT 1";
        $query = mysqli_query($mysqli, $sql);
        $count = mysqli_num_rows($query);

        if ($count > 0) {
            $row = mysqli_fetch_assoc($query);
            // Kiểm tra mật khẩu cũ
            if (password_verify($matkhau_cu, $row['matkhau'])) {
                // Mã hóa mật khẩu mới
                $matkhau_moi_mahoa = password_hash($password_moi_raw, PASSWORD_DEFAULT);

                $sql_update = mysqli_query($mysqli, "UPDATE table_dangky SET matkhau='".$matkhau_moi_mahoa."' WHERE email='".$taikhoan."'");
                $message = '<div class="alert alert-success">Mật khẩu đã được thay đổi thành công.</div>';
            } else {
                $message = '<div class="alert alert-error">Mật khẩu cũ không đúng.</div>';
            }
        } else {
            $message = '<div class="alert alert-error">Tài khoản không tồn tại.</div>';
        }
    }
}
?>

<div class="maincontent">
    <div class="content">
        <div class="content_right">
            <div class="auth-card">
                <div class="auth-head">
                    <div class="auth-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 1a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5Zm-3 8V6a3 3 0 0 1 6 0v3H9Z"/></svg>
                    </div>
                    <div>
                        <div class="auth-title">Đổi mật khẩu</div>
                        <div class="auth-subtitle">Tạo mật khẩu mạnh để bảo vệ tài khoản của bạn.</div>
                    </div>
                </div>

                <?php if (isset($message)) echo $message; ?>

                <form action="" autocomplete="off" method="POST" class="auth-form">
                    <div class="auth-grid is-single">
                        <div class="field">
                            <label for="pw_email">Tài khoản (Email)</label>
                            <div class="input-shell">
                                <span class="input-leading" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M20 5H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2Zm0 2l-8 5L4 7h16Zm0 10H4V9l8 5l8-5v8Z"/></svg>
                                </span>
                                <input id="pw_email" type="email" name="email" placeholder="email@example.com" autocomplete="email" required>
                            </div>
                        </div>

                        <div class="field">
                            <label for="pw_old">Mật khẩu cũ</label>
                            <div class="input-shell has-toggle">
                                <span class="input-leading" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 1a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5Zm-3 8V6a3 3 0 0 1 6 0v3H9Z"/></svg>
                                </span>
                                <input id="pw_old" type="password" name="password_cu" autocomplete="current-password" required>
                                <button type="button" class="input-trailing js-toggle-password" data-target="pw_old" aria-label="Ẩn/hiện mật khẩu" aria-pressed="false">
                                    <svg class="icon-eye" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 5c5.5 0 9.7 5.2 9.9 5.4a1 1 0 0 1 0 1.2C21.7 11.8 17.5 17 12 17S2.3 11.8 2.1 11.6a1 1 0 0 1 0-1.2C2.3 10.2 6.5 5 12 5Zm0 10a4 4 0 1 0-4-4a4 4 0 0 0 4 4Z"/></svg>
                                    <svg class="icon-eye-off" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M3.3 2.3a1 1 0 0 1 1.4 0l16 16a1 1 0 1 1-1.4 1.4l-2.1-2.1A10.9 10.9 0 0 1 12 19C6.5 19 2.3 13.8 2.1 13.6a1 1 0 0 1 0-1.2A18.6 18.6 0 0 1 6.2 8.3L3.3 5.4a1 1 0 0 1 0-1.4ZM12 7c-.5 0-1 .05-1.5.14l2.1 2.1A4 4 0 0 1 15.7 12l2.2 2.2a18.2 18.2 0 0 0 2-2.6C18.7 10.2 15.5 7 12 7Zm-6.1 4.1a18.2 18.2 0 0 0-1.8 2.5C5.3 15 8.5 18 12 18c1.4 0 2.7-.4 3.8-1l-1.6-1.6A4 4 0 0 1 8.6 9.8L5.9 7.1Z"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="field">
                            <label for="pw_new">Mật khẩu mới</label>
                            <div class="input-shell has-toggle">
                                <span class="input-leading" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 1a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5Zm-3 8V6a3 3 0 0 1 6 0v3H9Z"/></svg>
                                </span>
                                <input id="pw_new" type="password" name="password_moi" autocomplete="new-password" required>
                                <button type="button" class="input-trailing js-toggle-password" data-target="pw_new" aria-label="Ẩn/hiện mật khẩu" aria-pressed="false">
                                    <svg class="icon-eye" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 5c5.5 0 9.7 5.2 9.9 5.4a1 1 0 0 1 0 1.2C21.7 11.8 17.5 17 12 17S2.3 11.8 2.1 11.6a1 1 0 0 1 0-1.2C2.3 10.2 6.5 5 12 5Zm0 10a4 4 0 1 0-4-4a4 4 0 0 0 4 4Z"/></svg>
                                    <svg class="icon-eye-off" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M3.3 2.3a1 1 0 0 1 1.4 0l16 16a1 1 0 1 1-1.4 1.4l-2.1-2.1A10.9 10.9 0 0 1 12 19C6.5 19 2.3 13.8 2.1 13.6a1 1 0 0 1 0-1.2A18.6 18.6 0 0 1 6.2 8.3L3.3 5.4a1 1 0 0 1 0-1.4ZM12 7c-.5 0-1 .05-1.5.14l2.1 2.1A4 4 0 0 1 15.7 12l2.2 2.2a18.2 18.2 0 0 0 2-2.6C18.7 10.2 15.5 7 12 7Zm-6.1 4.1a18.2 18.2 0 0 0-1.8 2.5C5.3 15 8.5 18 12 18c1.4 0 2.7-.4 3.8-1l-1.6-1.6A4 4 0 0 1 8.6 9.8L5.9 7.1Z"/></svg>
                                </button>
                            </div>
                            <div class="field-hint">Tối thiểu 9 ký tự, gồm chữ cái và chữ số.</div>
                        </div>

                        <div class="field">
                            <label for="pw_confirm">Xác nhận mật khẩu mới</label>
                            <div class="input-shell has-toggle">
                                <span class="input-leading" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 1a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5Zm-3 8V6a3 3 0 0 1 6 0v3H9Z"/></svg>
                                </span>
                                <input id="pw_confirm" type="password" name="password_xacnhan" autocomplete="new-password" required>
                                <button type="button" class="input-trailing js-toggle-password" data-target="pw_confirm" aria-label="Ẩn/hiện mật khẩu" aria-pressed="false">
                                    <svg class="icon-eye" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 5c5.5 0 9.7 5.2 9.9 5.4a1 1 0 0 1 0 1.2C21.7 11.8 17.5 17 12 17S2.3 11.8 2.1 11.6a1 1 0 0 1 0-1.2C2.3 10.2 6.5 5 12 5Zm0 10a4 4 0 1 0-4-4a4 4 0 0 0 4 4Z"/></svg>
                                    <svg class="icon-eye-off" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M3.3 2.3a1 1 0 0 1 1.4 0l16 16a1 1 0 1 1-1.4 1.4l-2.1-2.1A10.9 10.9 0 0 1 12 19C6.5 19 2.3 13.8 2.1 13.6a1 1 0 0 1 0-1.2A18.6 18.6 0 0 1 6.2 8.3L3.3 5.4a1 1 0 0 1 0-1.4ZM12 7c-.5 0-1 .05-1.5.14l2.1 2.1A4 4 0 0 1 15.7 12l2.2 2.2a18.2 18.2 0 0 0 2-2.6C18.7 10.2 15.5 7 12 7Zm-6.1 4.1a18.2 18.2 0 0 0-1.8 2.5C5.3 15 8.5 18 12 18c1.4 0 2.7-.4 3.8-1l-1.6-1.6A4 4 0 0 1 8.6 9.8L5.9 7.1Z"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="auth-actions">
                        <button class="btn-primary" type="submit" name="doimatkhau" value="1">Đổi mật khẩu</button>
                        <a class="btn-secondary" href="index.php">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
