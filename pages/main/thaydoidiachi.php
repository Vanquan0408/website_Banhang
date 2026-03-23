<?php
// session may already have been started by index.php; start only if none
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
// ensure db config is available even when this file is accessed directly
if (!isset($mysqli)) {
    include_once(__DIR__ . "/../../admincp/config/config.php");
}

if (!isset($_SESSION['id_khachhang'])) {
    echo '<div class="alert alert-error" style="max-width:760px;margin:16px auto;">Bạn chưa đăng nhập!</div>';
    exit();
}

$id_khachhang = $_SESSION['id_khachhang'];

// xử lý form
if (isset($_POST['update_address'])) {
    $ap   = mysqli_real_escape_string($mysqli, trim($_POST['ap']));
    $xa   = mysqli_real_escape_string($mysqli, trim($_POST['xa']));
    $tinh = mysqli_real_escape_string($mysqli, trim($_POST['tinh']));
    $phone = mysqli_real_escape_string($mysqli, trim($_POST['dienthoai']));
    $note = mysqli_real_escape_string($mysqli, trim($_POST['ghichu'] ?? ''));

    $diachi = $ap;
    if ($xa) $diachi .= ', ' . $xa;
    if ($tinh) $diachi .= ', ' . $tinh;

    $sql = "UPDATE table_dangky SET diachi='$diachi', dienthoai='$phone' WHERE id_dangky='$id_khachhang'";
    // nếu bảng có cột ap/xa/tinh/ghichu, cập nhật thêm
    if (mysqli_query($mysqli, "SHOW COLUMNS FROM table_dangky LIKE 'ap'")) {
        $sql = "UPDATE table_dangky SET diachi='$diachi', dienthoai='$phone', ap='$ap', xa='$xa', tinh='$tinh', ghichu='$note' WHERE id_dangky='$id_khachhang'";
    }
    if (mysqli_query($mysqli, $sql)) {
        $message = '<div class="alert alert-success">Cập nhật địa chỉ thành công.</div>';
    } else {
        $message = '<div class="alert alert-error">Có lỗi khi cập nhật. Vui lòng thử lại.</div>';
    }
}

// lấy thông tin hiện tại
$query = mysqli_query($mysqli, "SELECT * FROM table_dangky WHERE id_dangky='$id_khachhang' LIMIT 1");
$user = mysqli_fetch_assoc($query);
// tách địa chỉ đã lưu ra thành phần (nếu có)
$parts = array_map('trim', explode(',', $user['diachi']));
$ap_val = $parts[0] ?? '';
$xa_val = $parts[1] ?? '';
$tinh_val = $parts[2] ?? '';

?>
<div class="maincontent">
    <div class="content">
        <div class="content_right">
            <div class="auth-card">
                <div class="auth-head">
                    <div class="auth-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 14.5 9 2.5 2.5 0 0 1 12 11.5Z"/></svg>
                    </div>
                    <div>
                        <div class="auth-title">Chỉnh sửa địa chỉ</div>
                        <div class="auth-subtitle">Cập nhật thông tin giao hàng để đặt hàng nhanh hơn.</div>
                    </div>
                </div>

                <?php if (isset($message)) echo $message; ?>

                <form method="post" action="" class="auth-form" autocomplete="on">
                    <div class="auth-grid">
                        <div class="field">
                            <label for="addr_ap">Ấp</label>
                            <div class="input-shell">
                                <span class="input-leading" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 14.5 9 2.5 2.5 0 0 1 12 11.5Z"/></svg>
                                </span>
                                <input id="addr_ap" type="text" name="ap" value="<?php echo htmlspecialchars($ap_val); ?>" placeholder="Ví dụ: Ấp 1" required>
                            </div>
                        </div>

                        <div class="field">
                            <label for="addr_xa">Xã/Phường</label>
                            <div class="input-shell">
                                <span class="input-leading" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 14.5 9 2.5 2.5 0 0 1 12 11.5Z"/></svg>
                                </span>
                                <input id="addr_xa" type="text" name="xa" value="<?php echo htmlspecialchars($xa_val); ?>" placeholder="Ví dụ: Phường 10" required>
                            </div>
                        </div>

                        <div class="field">
                            <label for="addr_tinh">Tỉnh/Thành</label>
                            <div class="input-shell">
                                <span class="input-leading" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 14.5 9 2.5 2.5 0 0 1 12 11.5Z"/></svg>
                                </span>
                                <input id="addr_tinh" type="text" name="tinh" value="<?php echo htmlspecialchars($tinh_val); ?>" placeholder="Ví dụ: TP.HCM" required>
                            </div>
                        </div>

                        <div class="field">
                            <label for="addr_phone">Số điện thoại</label>
                            <div class="input-shell">
                                <span class="input-leading" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M6.6 10.8c1.4 2.7 3.9 5.2 6.6 6.6l2.2-2.2a1 1 0 0 1 1.01-.24c1.1.36 2.3.55 3.55.55a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.85 21 3 13.15 3 3a1 1 0 0 1 1-1h3.43a1 1 0 0 1 1 1c0 1.25.19 2.45.55 3.55a1 1 0 0 1-.24 1.01l-2.14 2.24Z"/></svg>
                                </span>
                                <input id="addr_phone" type="tel" name="dienthoai" value="<?php echo htmlspecialchars($user['dienthoai']); ?>" placeholder="09xxxxxxxx" autocomplete="tel" required>
                            </div>
                        </div>

                        <div class="field is-full">
                            <label for="addr_note">Ghi chú</label>
                            <div class="input-shell">
                                <span class="input-leading" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M4 3h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H7l-3 3V5a2 2 0 0 1 2-2Zm2 5a1 1 0 0 0 0 2h12a1 1 0 1 0 0-2H6Zm0 4a1 1 0 0 0 0 2h9a1 1 0 1 0 0-2H6Z"/></svg>
                                </span>
                                <textarea id="addr_note" name="ghichu" rows="3" placeholder="Ví dụ: Giao giờ hành chính..."><?php echo htmlspecialchars($user['ghichu'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="auth-actions">
                        <button class="btn-primary" type="submit" name="update_address" value="1">Cập nhật</button>
                        <a class="btn-secondary" href="index.php">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>