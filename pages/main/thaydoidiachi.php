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
<!-- style giống với trang đổi mật khẩu -->
<style>
    .wrapper_login {
        max-width: 600px;
        margin: 40px auto;
        padding: 20px;
        background: #fff;
        box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        border-radius: 8px;
    }
    .title-change-password {
        text-align: center;
        font-size: 22px;
        font-weight: bold;
        color: white;
        background: linear-gradient(45deg, #007bff, #0056b3);
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0px 4px 6px rgba(0,0,0,0.2);
    }
    .table_login {
        width: 100%;
        border-collapse: collapse;
    }
    .table_login td {
        padding: 10px;
        text-align: left;
    }
    .table_login input[type="text"],
    .table_login input[type="tel"],
    .table_login textarea {
        width: 95%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        transition: border 0.3s;
    }
    .table_login input[type="text"]:focus,
    .table_login input[type="tel"]:focus,
    .table_login textarea:focus {
        border-color: #007bff;
        outline: none;
    }
    .table_login input[type="submit"] {
        width: 100%;
        padding: 10px;
        border: none;
        background-color: #007bff;
        color: white;
        font-size: 16px;
        font-weight: bold;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s;
    }
    .table_login input[type="submit"]:hover {
        background-color: #0056b3;
    }
    p {
        margin-top: 10px;
        font-size: 14px;
    }
</style>
<div class="wrapper_login">
    <h3 class="title-change-password">Chỉnh sửa địa chỉ</h3>
    <?php if (isset($message)) echo $message; ?>
    <form method="post" action="">
        <table class="table_login">
            <tr>
                <td>Ấp</td>
                <td><input type="text" name="ap" value="<?php echo htmlspecialchars($ap_val); ?>" required></td>
            </tr>
            <tr>
                <td>Xã</td>
                <td><input type="text" name="xa" value="<?php echo htmlspecialchars($xa_val); ?>" required></td>
            </tr>
            <tr>
                <td>Tỉnh/Thành</td>
                <td><input type="text" name="tinh" value="<?php echo htmlspecialchars($tinh_val); ?>" required></td>
            </tr>
            <tr>
                <td>Số điện thoại</td>
                <td><input type="tel" name="dienthoai" value="<?php echo htmlspecialchars($user['dienthoai']); ?>" required></td>
            </tr>
            <tr>
                <td>Ghi chú</td>
                <td><textarea name="ghichu" rows="3"><?php echo htmlspecialchars($user['ghichu'] ?? ''); ?></textarea></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="update_address" value="Cập nhật"></td>
            </tr>
        </table>
    </form>
</div>