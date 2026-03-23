
<?php
    if(isset($_POST['dangnhap'])){
        $email = $_POST['email'];
        $matkhau = $_POST['password'];

        // Lấy dữ liệu người dùng theo email
        $sql = "SELECT * FROM table_dangky WHERE email='".$email."' LIMIT 1";
        $query = mysqli_query($mysqli, $sql);
        $count = mysqli_num_rows($query);

        if($count > 0){
            $row_data = mysqli_fetch_array($query);

            // So sánh mật khẩu người dùng nhập với mật khẩu đã mã hóa
            if(password_verify($matkhau, $row_data['matkhau'])){
                $_SESSION['dangky'] = $row_data['tenkhachhang'];
                $_SESSION['id_khachhang'] = $row_data['id_dangky'];            
                header("Location:index.php");
                exit;
            } else {
                echo '<p style="color: red">Mật khẩu không đúng. Vui lòng thử lại.</p>';
            }
        } else {
            echo '<p style="color: red">Tài khoản không tồn tại.</p>';
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
            <p class="cart-title">Đăng nhập khách hàng</p>

            <form action="" autocomplete="off" method="POST">
                <table class="cart-table" border="1" width="50%" style="border-collapse: collapse;">
                    <tr>
                        <td>Email</td>
                        <td><input type="email" size="50" name="email" placeholder="Email..." required></td>
                    </tr>
                    <tr>
                        <td>Mật khẩu</td>
                        <td><input type="password" size="50" name="password" placeholder="Mật khẩu..." required></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <input class="action-links delete-btn" type="submit" name="dangnhap" value="Đăng nhập" style="margin-bottom: 10px;">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>