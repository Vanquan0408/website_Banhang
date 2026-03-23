<?php
// session may already have been started by pages/main.php; start only if none
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
// include configuration file using absolute reference from this file's directory
// (pages/main -> go up two levels to root, then into admincp/config)
include(__DIR__ . "/../../admincp/config/config.php");

if (!isset($_SESSION['id_khachhang'])) {
    echo "<p style='color:red;text-align:center;'>Bạn chưa đăng nhập!</p>";
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
        $message = '<p style="color:green; text-align:center;">Cập nhật địa chỉ thành công</p>';
    } else {
        $message = '<p style="color:red; text-align:center;">Có lỗi khi cập nhật</p>';
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
<style>
    /* Tăng kích thước textbox và chữ cho trang chỉnh địa chỉ */
    .cart-table input[type="text"],
    .cart-table input[type="tel"],
    .cart-table textarea {
        font-size: 16px;
        padding: 10px;
        height: 40px;
        box-sizing: border-box;
    }
    .cart-table .action-links,
    .cart-table input[type="submit"] {
        font-size: 16px;
        padding: 10px 14px;
    }
</style>

<div class="maincontent">
    <div class="content">
        <div class="content_right">
            <p class="cart-title">Chỉnh sửa địa chỉ</p>

            <?php if (isset($message)) echo $message; ?>

            <form method="post" action="">
                <table class="cart-table" border="1" width="50%" style="border-collapse: collapse;">
                    <tr>
                        <td>Ấp</td>
                        <td><input type="text" size="50" name="ap" value="<?php echo htmlspecialchars($ap_val); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Xã</td>
                        <td><input type="text" size="50" name="xa" value="<?php echo htmlspecialchars($xa_val); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Tỉnh/Thành</td>
                        <td><input type="text" size="50" name="tinh" value="<?php echo htmlspecialchars($tinh_val); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Số điện thoại</td>
                        <td><input type="tel" size="50" name="dienthoai" value="<?php echo htmlspecialchars($user['dienthoai']); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Ghi chú</td>
                        <td><textarea name="ghichu" rows="3"><?php echo htmlspecialchars($user['ghichu'] ?? ''); ?></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <a href="index.php?quanly=dangnhap" class="danh_nhap">Đăng nhập</a>
                            <input class="action-links delete-btn" type="submit" name="update_address" value="Cập nhật" style="margin-bottom: 10px;">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>