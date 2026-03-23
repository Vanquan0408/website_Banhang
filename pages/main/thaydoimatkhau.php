<?php
if (isset($_POST['doimatkhau'])) {
    $taikhoan = $_POST['email'];
    $matkhau_cu = $_POST['password_cu'];
    $password_moi_raw = $_POST['password_moi'];
    $password_xacnhan_raw = $_POST['password_xacnhan'];

    if ($password_moi_raw !== $password_xacnhan_raw) {
        $message = '<p style="color:red">Mật khẩu mới và xác nhận không trùng khớp</p>';
    } elseif (
        strlen($password_moi_raw) < 9 || 
        !preg_match('/[A-Za-z]/', $password_moi_raw) || 
        !preg_match('/[0-9]/', $password_moi_raw)
    ) {
        $message = '<p style="color:red">Mật khẩu mới phải có ít nhất 9 ký tự và bao gồm cả chữ cái và chữ số</p>';
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
                $message = '<p style="color:green">Mật khẩu đã được thay đổi thành công</p>';
            } else {
                $message = '<p style="color:red">Mật khẩu cũ không đúng</p>';
            }
        } else {
            $message = '<p style="color:red">Tài khoản không tồn tại</p>';
        }
    }
}
?>

<style>
    /* Tăng kích thước textbox và chữ để hiển thị tốt hơn */
    .cart-table input[type="email"],
    .cart-table input[type="password"],
    .cart-table input[type="text"],
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
            <p class="cart-title">Đổi mật khẩu</p>

            <?php if (isset($message)) echo $message; ?>

            <form action="" autocomplete="off" method="POST">
                <table class="cart-table" border="1" width="50%" style="border-collapse: collapse;">
                    <tr>
                        <td>Tài khoản</td>
                        <td><input type="email" size="50" name="email" required></td>
                    </tr>
                    <tr>
                        <td>Mật khẩu cũ</td>
                        <td><input type="password" size="50" name="password_cu" required></td>
                    </tr>
                    <tr>
                        <td>Mật khẩu mới</td>
                        <td><input type="password" size="50" name="password_moi" required></td>
                    </tr>
                    <tr>
                        <td>Nhập lại mật khẩu</td>
                        <td><input type="password" size="50" name="password_xacnhan" required></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <a href="index.php?quanly=dangnhap" class="danh_nhap">Đăng nhập</a>
                            <input class="action-links delete-btn" type="submit" name="doimatkhau" value="Đổi mật khẩu" style="margin-bottom: 10px;">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
