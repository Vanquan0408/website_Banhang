<?php
session_start();
include("../../admincp/config/config.php");

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['id_khachhang'])) {
    echo "<p style='color:red; text-align:center;'>Bạn chưa đăng nhập!</p>";
    exit();
}

// Kiểm tra nếu giỏ hàng rỗng
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p style='color:red; text-align:center;'>Giỏ hàng của bạn đang trống!</p>";
    exit();
}

$id_khachhang = $_SESSION['id_khachhang'];
$code_order = rand(1000, 9999);
$tong_tien = 0;

// Lấy thông tin khách hàng
$query_khachhang = mysqli_query($mysqli, "SELECT * FROM table_dangky WHERE id_dangky = '$id_khachhang' LIMIT 1");
$khachhang = mysqli_fetch_assoc($query_khachhang);

// Lưu dữ liệu giỏ hàng vào biến tạm
$cart_data = $_SESSION['cart'];

// Thêm đơn hàng vào bảng giohang
$insert_cart = "INSERT INTO table_giohang (id_khachhang, code_cart, cart_status) VALUES ('$id_khachhang', '$code_order', 1)";
$cart_query = mysqli_query($mysqli, $insert_cart);

if ($cart_query) {
    foreach ($cart_data as $key => $value) {
        $id_sanpham = $value['id'];
        $soluong = $value['soluong'];
        $gia = $value['giasp'];
        $tong_tien += $gia * $soluong;

        $insert_order_details = "INSERT INTO table_chitietdonhang (id_sanpham, code_cart, soluongmua) VALUES ('$id_sanpham', '$code_order', '$soluong')";
        mysqli_query($mysqli, $insert_order_details);
    }
    unset($_SESSION['cart']);
    echo "<h2 style='color:green; text-align:center;'>Đặt hàng thành công!</h2>";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn Thanh Toán</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice-container { width: 50%; margin: auto; text-align: center; border: 1px solid #ccc; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        .total { font-weight: bold; color: red; }
        .delete-btn {
        background: #dc3545 !important;
        padding: 8px 12px;
        border-radius: 5px;
        text-decoration: none;
        color: white;
        font-size: 16px;
        transition: background 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <h2>Hóa Đơn Thanh Toán</h2>
        <p><strong>Mã đơn hàng:</strong> <?php echo $code_order; ?></p>
        <p><strong>Khách hàng:</strong> <?php echo $khachhang['tenkhachhang']; ?></p>
        <p><strong>Địa chỉ:</strong> <?php echo $khachhang['diachi']; ?></p>
        <p><strong>Số điện thoại:</strong> <?php echo $khachhang['dienthoai']; ?></p>
        <p><strong>Ngày đặt:</strong> <?php echo date("d/m/Y H:i:s"); ?></p>
        <table>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Thành tiền</th>
            </tr>
            <?php foreach ($cart_data as $value): ?>
                <tr>
                    <td><?php echo $value['tensanpham']; ?></td>
                    <td><?php echo $value['soluong']; ?></td>
                    <td><?php echo number_format($value['giasp'], 0, ',', '.') . ' VND'; ?></td>
                    <td><?php echo number_format($value['giasp'] * $value['soluong'], 0, ',', '.') . ' VND'; ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" class="total">Tổng cộng:</td>
                <td class="total"><?php echo number_format($tong_tien, 0, ',', '.') . ' VND'; ?></td>
            </tr>
        </table>
        <p>Cảm ơn bạn đã mua hàng!</p>
        <a class="delete-btn" href="../../index.php">Quay lại trang chủ</a>
    </div>
</body>
</html>
