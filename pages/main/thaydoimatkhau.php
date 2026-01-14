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


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .wrapper_login {
            width: 1000px;
            padding: 20px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        h3 {
            margin-bottom: 20px;
            color: #333;
            text-align: center
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
        .table_login input[type="password"] {
            width: 95%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border 0.3s;
        }

        .table_login input[type="text"]:focus,
        .table_login input[type="password"]:focus {
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
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        }
        </style>
</head>
<body>
<div class="wrapper_login">
    <h3 class="title-change-password">Đổi mật khẩu</h3>
    <?php if (isset($message)) echo $message; ?>
    <form action="" autocomplete="off" method="POST">
        <table class="table_login">
            <tr>
                <td>Tài khoản</td>
                <td><input type="text" name="email" required></td>
            </tr>
            <tr>
                <td>Mật khẩu cũ</td>
                <td><input type="password" name="password_cu" required></td>
            </tr>
            <tr>
                <td>Mật khẩu mới</td>
                <td><input type="password" name="password_moi" required></td>
            </tr>
            <tr>
                <td>Xác nhận mật khẩu</td>
                <td><input type="password" name="password_xacnhan" required></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="doimatkhau" value="Đổi mật khẩu"></td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
