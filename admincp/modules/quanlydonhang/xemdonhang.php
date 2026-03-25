<?php


$code = $_GET['code'];

// Best-effort DB migration for detailed order status (avoid duplicate-column fatal)
$hasOrderStatus = false;
try {
    $qHas = mysqli_query($mysqli, "SHOW COLUMNS FROM table_giohang LIKE 'order_status'");
    if ($qHas && mysqli_num_rows($qHas) > 0) {
        $hasOrderStatus = true;
    }
} catch (mysqli_sql_exception $e) {
    $hasOrderStatus = false;
}
if (!$hasOrderStatus) {
    try {
        mysqli_query($mysqli, "ALTER TABLE table_giohang ADD COLUMN order_status TINYINT NOT NULL DEFAULT 1");
        $hasOrderStatus = true;
    } catch (mysqli_sql_exception $e) {
        $hasOrderStatus = false;
    }
}

// Best-effort DB migration for cancel request flag
$hasCancelRequested = false;
try {
    $qHas = mysqli_query($mysqli, "SHOW COLUMNS FROM table_giohang LIKE 'cancel_requested'");
    if ($qHas && mysqli_num_rows($qHas) > 0) {
        $hasCancelRequested = true;
    }
} catch (mysqli_sql_exception $e) {
    $hasCancelRequested = false;
}
if (!$hasCancelRequested) {
    try {
        mysqli_query($mysqli, "ALTER TABLE table_giohang ADD COLUMN cancel_requested TINYINT NOT NULL DEFAULT 0");
        $hasCancelRequested = true;
    } catch (mysqli_sql_exception $e) {
        $hasCancelRequested = false;
    }
}

// Best-effort DB migration for cancel reason
$hasCancelReason = false;
try {
    $qHas = mysqli_query($mysqli, "SHOW COLUMNS FROM table_giohang LIKE 'cancel_reason'");
    if ($qHas && mysqli_num_rows($qHas) > 0) {
        $hasCancelReason = true;
    }
} catch (mysqli_sql_exception $e) {
    $hasCancelReason = false;
}
if (!$hasCancelReason) {
    try {
        mysqli_query($mysqli, "ALTER TABLE table_giohang ADD COLUMN cancel_reason TEXT NULL");
        $hasCancelReason = true;
    } catch (mysqli_sql_exception $e) {
        $hasCancelReason = false;
    }
}

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
                         table_giohang.dienthoai AS order_dienthoai,
                         table_giohang.order_status AS order_status,
                         table_giohang.cancel_requested AS cancel_requested,
                         table_giohang.cancel_reason AS cancel_reason
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

            <div class="admin-divider"></div>
            <p class="table-title">Trạng thái đơn hàng</p>

            <?php
                $cur = isset($khachhang['order_status']) ? (int)$khachhang['order_status'] : 1;
                $cancelRequested = isset($khachhang['cancel_requested']) ? (int)$khachhang['cancel_requested'] : 0;
            ?>

            <?php if ($hasCancelRequested && $cancelRequested === 1 && $cur !== 5) { ?>
                <div class="admin-alert admin-alert--info" role="alert" style="margin: 10px 0 0;">
                    <div class="admin-alert-icon">!</div>
                    <div>
                        <div class="admin-alert-title">Yêu cầu hủy đơn</div>
                        <div class="admin-alert-desc">Khách hàng đã gửi yêu cầu hủy. Bạn có thể duyệt hủy hoặc từ chối.</div>
                        <?php if ($hasCancelReason && !empty($khachhang['cancel_reason'])) { ?>
                            <div class="admin-alert-desc" style="margin-top:6px;">Lý do: <?php echo htmlspecialchars((string)$khachhang['cancel_reason']); ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="admin-actions" style="margin-top: 10px; flex-wrap: wrap;">
                    <form method="post" action="modules/quanlydonhang/xuly.php?action=approve_cancel&code=<?php echo htmlspecialchars($code); ?>" onsubmit="return confirm('Duyệt hủy đơn hàng này?');">
                        <button type="submit" class="btn delete-btn">Duyệt hủy</button>
                    </form>
                    <form method="post" action="modules/quanlydonhang/xuly.php?action=reject_cancel&code=<?php echo htmlspecialchars($code); ?>" onsubmit="return confirm('Từ chối yêu cầu hủy?');">
                        <button type="submit" class="btn edit-btn">Từ chối hủy</button>
                    </form>
                </div>
            <?php } ?>

            <form method="post" action="modules/quanlydonhang/xuly.php?action=update_order_status&code=<?php echo htmlspecialchars($code); ?>" class="styled-form">
                <div style="display:grid; gap:10px;">
                    <label class="form-label">Cập nhật trạng thái</label>
                    <select name="order_status" class="form-select">
                        <?php
                            $opts = [
                                1 => 'Chờ thanh toán',
                                2 => 'Vận chuyển',
                                3 => 'Chờ giao hàng',
                                4 => 'Hoàn thành',
                                5 => 'Đã hủy',
                                6 => 'Trả hàng/Hoàn tiền',
                            ];
                            foreach ($opts as $k => $v) {
                                $sel = ($cur === $k) ? 'selected' : '';
                                echo '<option value="' . (int)$k . '" ' . $sel . '>' . htmlspecialchars($v) . '</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="admin-actions">
                    <button type="submit" class="btn submit-btn">Lưu trạng thái</button>
                </div>
            </form>

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
