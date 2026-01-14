<?php
session_start();
include('config/config.php');

if(isset($_POST['dangnhap'])){
    $taikhoan = $_POST['username'];
    $matkhau = md5($_POST['password']);
    $stmt = $mysqli->prepare("SELECT * FROM admin WHERE username=? AND password=? LIMIT 1");
    $stmt->bind_param("ss", $taikhoan, $matkhau);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;

    if($count > 0){
        $_SESSION['dangnhap'] = $taikhoan;
        header("Location: index.php");
        exit();
    } else {
        echo '<script>alert("Tài khoản hoặc mật khẩu sai!"); window.location.href="login.php";</script>';
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript"src="js_VQ/jquery-3.7.1.js"> </script>
        <script type="text/javascript"src="js_VQ/jscript.js"></script>
        <title>Trang đăng nhập </title> 
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
    width: 350px;
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
<div class="warpper_login">
    <form action=""  autocomplete="off" method="POST">
    <table class="table_login" border="1" style="text_align: center">
        <tr>
            <td colspan ="2"><h3 class="title-change-password">Đăng nhập Admin</h3></td>
        </tr>
        <tr>
            <td>Tài khoản</td>
            <td><input type="text" name="username"></td>
        </tr>
        <tr>
            <td>Mật Khẩu</td>
            <td ><input type="password" name="password"></td>
        </tr>
        <tr>
        <td colspan="2"><input type="submit" name="dangnhap" value="Đăng nhập"></td>
        </tr>
    </table>
    </form>
</div>  
<script> type"text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"</script>
</body>
</html>