<?php


$code = $_GET['code'];

// Truy vấn thông tin chi tiết đơn hàng
$sql_lietke_dh = "SELECT * FROM table_chitietdonhang, sanpham 
                   WHERE table_chitietdonhang.id_sanpham = sanpham.id_sanpham
                   AND table_chitietdonhang.code_cart = '".$code."'
                   ORDER BY table_chitietdonhang.id_cart_details DESC";
$query_lietke_dh = mysqli_query($mysqli ,$sql_lietke_dh);

// Truy vấn thông tin khách hàng
$sql_khachhang = "SELECT table_dangky.tenkhachhang, table_dangky.diachi, table_dangky.dienthoai, table_dangky.email
                  FROM table_giohang
                  JOIN table_dangky ON table_giohang.id_khachhang = table_dangky.id_dangky
                  WHERE table_giohang.code_cart = '$code' LIMIT 1";
$query_khachhang = mysqli_query($mysqli, $sql_khachhang);
$khachhang = mysqli_fetch_assoc($query_khachhang);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hóa đơn</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice-box { width: 80%; margin: auto; padding: 20px; border: 1px solid #ddd; }
        .invoice-box table { width: 100%; border-collapse: collapse; }
        .invoice-box table th, .invoice-box table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .print-btn { margin-top: 20px; padding: 10px; background: #28a745; color: white; border: none; cursor: pointer; }
        .print-btn:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h2>HÓA ĐƠN MUA HÀNG</h2>
        <p><strong>Khách hàng:</strong> <?php echo htmlspecialchars($khachhang['tenkhachhang']); ?></p>
        <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($khachhang['diachi']); ?></p>
        <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($khachhang['dienthoai']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($khachhang['email']); ?></p>

        <table>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
            <?php
            $i = 0;
            $tongtien = 0;
            while ($row = mysqli_fetch_array($query_lietke_dh)) {
                $i++;
                $thanhtien = $row['giasp'] * $row['soluongmua'];
                $tongtien += $thanhtien;
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $row['tensanpham']; ?></td>
                <td><?php echo $row['soluongmua']; ?></td>
                <td><?php echo number_format($row['giasp'], 0, ',', '.'); ?> VND</td>
                <td><?php echo number_format($thanhtien, 0, ',', '.'); ?> VND</td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="4"><strong>Tổng tiền:</strong></td>
                <td><strong><?php echo number_format($tongtien, 0, ',', '.'); ?> VND</strong></td>
            </tr>
        </table>
        <button class="print-btn" onclick="window.print()">In hóa đơn</button>
    </div>
</body>
</html>
