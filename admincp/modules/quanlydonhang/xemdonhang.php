<?php


$code = $_GET['code'];

// Truy vấn thông tin chi tiết đơn hàng
$sql_lietke_dh = "SELECT * FROM table_chitietdonhang, sanpham 
                   WHERE table_chitietdonhang.id_sanpham = sanpham.id_sanpham
                   AND table_chitietdonhang.code_cart = '".$code."'
                   ORDER BY table_chitietdonhang.id_cart_details DESC";
$query_lietke_dh = mysqli_query($mysqli ,$sql_lietke_dh);

// Truy vấn thông tin khách hàng
$sql_khachhang = "SELECT table_dangky.tenkhachhang,
                         table_dangky.email,
                         table_dangky.diachi AS default_diachi,
                         table_dangky.dienthoai AS default_dienthoai,
                         table_giohang.ap AS order_ap,
                         table_giohang.xa AS order_xa,
                         table_giohang.tinh AS order_tinh,
                         table_giohang.ghichu AS order_ghichu,
                         table_giohang.dienthoai AS order_dienthoai
                  FROM table_giohang
                  JOIN table_dangky ON table_giohang.id_khachhang = table_dangky.id_dangky
                  WHERE table_giohang.code_cart = '$code' LIMIT 1";
$query_khachhang = mysqli_query($mysqli, $sql_khachhang);
$khachhang = mysqli_fetch_assoc($query_khachhang);
?>

<section class="admin-panel admin-panel--page">
    <div class="admin-page-head">
        <div>
            <div class="admin-page-title">Hóa đơn mua hàng</div>
            <div class="admin-page-sub">Mã đơn: <strong><?php echo htmlspecialchars($code); ?></strong></div>
        </div>
        <div class="admin-actions">
            <a class="btn" href="index.php?action=quanlydonhang&query=lietke">Quay lại</a>
            <button type="button" class="btn submit-btn" onclick="window.print()">In hóa đơn</button>
        </div>
    </div>

    <?php
        $addr = '';
        if (!empty($khachhang['order_ap'])) {
            $addr .= $khachhang['order_ap'];
            if (!empty($khachhang['order_xa'])) $addr .= ', ' . $khachhang['order_xa'];
            if (!empty($khachhang['order_tinh'])) $addr .= ', ' . $khachhang['order_tinh'];
        } else {
            $addr = $khachhang['default_diachi'];
        }
    ?>

    <div class="admin-two-col admin-two-col--details">
        <div class="admin-panel">
            <p class="table-title">Thông tin khách hàng</p>
            <div class="admin-kv">
                <div class="admin-kv-row"><span class="admin-kv-label">Khách hàng</span><span class="admin-kv-value"><?php echo htmlspecialchars($khachhang['tenkhachhang']); ?></span></div>
                <div class="admin-kv-row"><span class="admin-kv-label">Số điện thoại</span><span class="admin-kv-value"><?php echo htmlspecialchars(!empty($khachhang['order_dienthoai']) ? $khachhang['order_dienthoai'] : $khachhang['default_dienthoai']); ?></span></div>
                <div class="admin-kv-row"><span class="admin-kv-label">Email</span><span class="admin-kv-value"><?php echo htmlspecialchars($khachhang['email']); ?></span></div>
                <div class="admin-kv-row"><span class="admin-kv-label">Địa chỉ</span><span class="admin-kv-value"><?php echo htmlspecialchars($addr); ?></span></div>
            </div>

            <?php if (!empty($khachhang['order_ghichu'])): ?>
                <div class="admin-divider"></div>
                <p class="table-title">Ghi chú</p>
                <div class="admin-help"><?php echo htmlspecialchars($khachhang['order_ghichu']); ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="admin-panel">
        <p class="table-title">Danh sách sản phẩm</p>
        <div class="table-wrap">
            <table class="styled-table" border="1px">
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
                    <td><?php echo htmlspecialchars($row['tensanpham']); ?></td>
                    <td><?php echo (int)$row['soluongmua']; ?></td>
                    <td><?php echo number_format((int)$row['giasp'], 0, ',', '.'); ?> VND</td>
                    <td><?php echo number_format((int)$thanhtien, 0, ',', '.'); ?> VND</td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="4" style="text-align:right;"><strong>Tổng tiền</strong></td>
                    <td><strong><?php echo number_format((int)$tongtien, 0, ',', '.'); ?> VND</strong></td>
                </tr>
            </table>
        </div>
    </div>
</section>
