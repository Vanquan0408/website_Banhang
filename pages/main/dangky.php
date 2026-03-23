<?php
    if(isset($_POST['dangky'])){
        $tenkhachhang = $_POST['hovaten'];
        $email = $_POST['email'];
        $dienthoai = $_POST['dienthoai'];
        $diachi = $_POST['diachi'];
        $matkhau = $_POST['matkhau'];
        $matkhau_laplai = $_POST['matkhau_laplai'];

        // Kiểm tra mật khẩu
        if(strlen($matkhau) < 9 || !preg_match('/[a-zA-Z]/', $matkhau)){
            echo '<div class="alert alert-error">Mật khẩu phải có ít nhất 9 ký tự và chứa ít nhất một chữ cái.</div>';
        } elseif($matkhau !== $matkhau_laplai){
            echo '<div class="alert alert-error">Mật khẩu nhập lại không khớp.</div>';
        } else {
            // Kiểm tra trùng email
            $check_email = mysqli_query($mysqli, "SELECT * FROM table_dangky WHERE email='".$email."' LIMIT 1");
            if(mysqli_num_rows($check_email) > 0){
                echo '<div class="alert alert-error">Email này đã được đăng ký. Vui lòng dùng email khác.</div>';
            } else {
                // Mã hóa mật khẩu bằng password_hash
                $matkhau_mahoa = password_hash($matkhau, PASSWORD_DEFAULT);

                $sql_dangky = mysqli_query($mysqli, "INSERT INTO table_dangky(tenkhachhang, email, diachi, matkhau, dienthoai) 
                VALUE('".$tenkhachhang."','".$email."','".$diachi."','".$matkhau_mahoa."','".$dienthoai."')");

                if($sql_dangky){
                    echo '<div class="alert alert-success">Bạn đã đăng ký thành công.</div>';
                    $_SESSION['dangky'] = $tenkhachhang;
                    $_SESSION['id_khachhang'] = mysqli_insert_id($mysqli);
                    header('Location:index.php?quanly=giohang');
                    exit;
                }
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
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2c-4.1 0-7.5 2.2-7.5 5a1 1 0 1 0 2 0c0-1.5 2.5-3 5.5-3s5.5 1.5 5.5 3a1 1 0 1 0 2 0c0-2.8-3.4-5-7.5-5Z"/></svg>
                    </div>
                    <div>
                        <div class="auth-title">Tạo tài khoản</div>
                        <div class="auth-subtitle">Đăng ký nhanh để mua hàng và theo dõi đơn.</div>
                    </div>
                </div>

                <form action="" method="POST" class="auth-form" autocomplete="on">
                    <div class="auth-grid">
                        <div class="field">
                            <label for="signup_name">Họ và tên</label>
                            <div class="input-shell">
                                <span class="input-leading" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2c-4.1 0-7.5 2.2-7.5 5a1 1 0 1 0 2 0c0-1.5 2.5-3 5.5-3s5.5 1.5 5.5 3a1 1 0 1 0 2 0c0-2.8-3.4-5-7.5-5Z"/></svg>
                                </span>
                                <input id="signup_name" type="text" name="hovaten" placeholder="Nguyễn Văn A" autocomplete="name" required>
                            </div>
                        </div>

                        <div class="field">
                            <label for="signup_email">Email</label>
                            <div class="input-shell">
                                <span class="input-leading" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M20 5H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2Zm0 2l-8 5L4 7h16Zm0 10H4V9l8 5l8-5v8Z"/></svg>
                                </span>
                                <input id="signup_email" type="email" name="email" placeholder="email@example.com" autocomplete="email" required>
                            </div>
                        </div>

                        <div class="field">
                            <label for="signup_phone">Điện thoại</label>
                            <div class="input-shell">
                                <span class="input-leading" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M6.6 10.8c1.4 2.7 3.9 5.2 6.6 6.6l2.2-2.2a1 1 0 0 1 1.01-.24c1.1.36 2.3.55 3.55.55a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.85 21 3 13.15 3 3a1 1 0 0 1 1-1h3.43a1 1 0 0 1 1 1c0 1.25.19 2.45.55 3.55a1 1 0 0 1-.24 1.01l-2.14 2.24Z"/></svg>
                                </span>
                                <input id="signup_phone" type="tel" name="dienthoai" placeholder="09xxxxxxxx" autocomplete="tel" required>
                            </div>
                        </div>

                        <div class="field">
                            <label for="signup_address">Địa chỉ</label>
                            <div class="input-shell">
                                <span class="input-leading" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 14.5 9 2.5 2.5 0 0 1 12 11.5Z"/></svg>
                                </span>
                                <input id="signup_address" type="text" name="diachi" placeholder="Số nhà, đường, phường/xã..." autocomplete="street-address" required>
                            </div>
                        </div>

                        <div class="field">
                            <label for="signup_password">Mật khẩu</label>
                            <div class="input-shell has-toggle">
                                <span class="input-leading" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 1a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5Zm-3 8V6a3 3 0 0 1 6 0v3H9Z"/></svg>
                                </span>
                                <input id="signup_password" type="password" name="matkhau" autocomplete="new-password"
                                    pattern="(?=.*[A-Za-z]).{9,}"
                                    title="Mật khẩu phải ít nhất 9 ký tự và chứa ít nhất một chữ cái."
                                    required>
                                <button type="button" class="input-trailing js-toggle-password" data-target="signup_password" aria-label="Ẩn/hiện mật khẩu" aria-pressed="false">
                                    <svg class="icon-eye" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 5c5.5 0 9.7 5.2 9.9 5.4a1 1 0 0 1 0 1.2C21.7 11.8 17.5 17 12 17S2.3 11.8 2.1 11.6a1 1 0 0 1 0-1.2C2.3 10.2 6.5 5 12 5Zm0 10a4 4 0 1 0-4-4a4 4 0 0 0 4 4Z"/></svg>
                                    <svg class="icon-eye-off" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M3.3 2.3a1 1 0 0 1 1.4 0l16 16a1 1 0 1 1-1.4 1.4l-2.1-2.1A10.9 10.9 0 0 1 12 19C6.5 19 2.3 13.8 2.1 13.6a1 1 0 0 1 0-1.2A18.6 18.6 0 0 1 6.2 8.3L3.3 5.4a1 1 0 0 1 0-1.4ZM12 7c-.5 0-1 .05-1.5.14l2.1 2.1A4 4 0 0 1 15.7 12l2.2 2.2a18.2 18.2 0 0 0 2-2.6C18.7 10.2 15.5 7 12 7Zm-6.1 4.1a18.2 18.2 0 0 0-1.8 2.5C5.3 15 8.5 18 12 18c1.4 0 2.7-.4 3.8-1l-1.6-1.6A4 4 0 0 1 8.6 9.8L5.9 7.1Z"/></svg>
                                </button>
                            </div>
                            <div class="field-hint">Tối thiểu 9 ký tự, có ít nhất 1 chữ cái.</div>
                        </div>

                        <div class="field">
                            <label for="signup_password2">Nhập lại mật khẩu</label>
                            <div class="input-shell has-toggle">
                                <span class="input-leading" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 1a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5Zm-3 8V6a3 3 0 0 1 6 0v3H9Z"/></svg>
                                </span>
                                <input id="signup_password2" type="password" name="matkhau_laplai" autocomplete="new-password"
                                    pattern="(?=.*[A-Za-z]).{9,}"
                                    title="Mật khẩu phải giống với mật khẩu phía trên."
                                    required>
                                <button type="button" class="input-trailing js-toggle-password" data-target="signup_password2" aria-label="Ẩn/hiện mật khẩu" aria-pressed="false">
                                    <svg class="icon-eye" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 5c5.5 0 9.7 5.2 9.9 5.4a1 1 0 0 1 0 1.2C21.7 11.8 17.5 17 12 17S2.3 11.8 2.1 11.6a1 1 0 0 1 0-1.2C2.3 10.2 6.5 5 12 5Zm0 10a4 4 0 1 0-4-4a4 4 0 0 0 4 4Z"/></svg>
                                    <svg class="icon-eye-off" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M3.3 2.3a1 1 0 0 1 1.4 0l16 16a1 1 0 1 1-1.4 1.4l-2.1-2.1A10.9 10.9 0 0 1 12 19C6.5 19 2.3 13.8 2.1 13.6a1 1 0 0 1 0-1.2A18.6 18.6 0 0 1 6.2 8.3L3.3 5.4a1 1 0 0 1 0-1.4ZM12 7c-.5 0-1 .05-1.5.14l2.1 2.1A4 4 0 0 1 15.7 12l2.2 2.2a18.2 18.2 0 0 0 2-2.6C18.7 10.2 15.5 7 12 7Zm-6.1 4.1a18.2 18.2 0 0 0-1.8 2.5C5.3 15 8.5 18 12 18c1.4 0 2.7-.4 3.8-1l-1.6-1.6A4 4 0 0 1 8.6 9.8L5.9 7.1Z"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="auth-actions">
                        <button class="btn-primary" type="submit" name="dangky" value="1">
                            Đăng ký
                        </button>
                        <a class="btn-secondary" href="index.php?quanly=dangnhap">Đăng nhập</a>
                    </div>

                    <div class="auth-footer">Bằng việc đăng ký, bạn đồng ý với các điều khoản sử dụng của cửa hàng.</div>
                </form>
            </div>
        </div>
    </div>
</div>
