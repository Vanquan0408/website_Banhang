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
            echo '<p style="color:red">Mật khẩu phải có ít nhất 9 ký tự và chứa ít nhất một chữ cái.</p>';
        } elseif($matkhau !== $matkhau_laplai){
            echo '<p style="color:red">Mật khẩu nhập lại không khớp.</p>';
        } else {
            // Kiểm tra trùng email
            $check_email = mysqli_query($mysqli, "SELECT * FROM table_dangky WHERE email='".$email."' LIMIT 1");
            if(mysqli_num_rows($check_email) > 0){
                echo '<p style="color:red">Email này đã được đăng ký. Vui lòng dùng email khác.</p>';
            } else {
                // Mã hóa mật khẩu bằng password_hash
                $matkhau_mahoa = password_hash($matkhau, PASSWORD_DEFAULT);

                $sql_dangky = mysqli_query($mysqli, "INSERT INTO table_dangky(tenkhachhang, email, diachi, matkhau, dienthoai) 
                VALUE('".$tenkhachhang."','".$email."','".$diachi."','".$matkhau_mahoa."','".$dienthoai."')");

                if($sql_dangky){
                    echo '<p style="color:green">Bạn đã đăng ký thành công </p>';
                    $_SESSION['dangky'] = $tenkhachhang;
                    $_SESSION['id_khachhang'] = mysqli_insert_id($mysqli);
                    header('Location:index.php?quanly=giohang');
                }
            }
        }
    }
?>
<p class="cart-title">Đăng ký thành viên</p>

<form action="" method="POST">
    <table class="cart-table" border="1" width="50%" style="border-collapse: collapse;">
        <tr>
            <td>Họ và tên</td>
            <td><input type="text" size="50" name="hovaten" required></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input type="email" size="50" name="email" required></td>
        </tr>
        <tr>
            <td>Điện thoại</td>
            <td><input type="text" size="50" name="dienthoai" required></td>
        </tr>
        <tr>
            <td>Địa chỉ</td>
            <td><input type="text" size="50" name="diachi" required></td>
        </tr>
        <tr>
            <td>Mật khẩu</td>
            <td>
                <input type="password" size="50" name="matkhau"
                    pattern="(?=.*[A-Za-z]).{9,}"
                    title="Mật khẩu phải ít nhất 9 ký tự và chứa ít nhất một chữ cái."
                    required>
            </td>
        </tr>
        <tr>
            <td>Nhập lại mật khẩu</td>
            <td>
                <input type="password" size="50" name="matkhau_laplai"
                    pattern="(?=.*[A-Za-z]).{9,}"
                    title="Mật khẩu phải giống với mật khẩu phía trên." 
                    required>
            </td>
        </tr>
        <tr>
    <td colspan="2" style="text-align: center;">
    <a href="index.php?quanly=dangnhap" class="danh_nhap">Đăng nhập</a>
    <input class="action-links delete-btn" type="submit" name="dangky" value="Đăng ký" style="margin-bottom: 10px;">
    </td>
    </tr>

    </table>
</form>
