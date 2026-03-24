<?php
// xử lý đặt hàng và thiết lập cờ hiển thị giỏ hàng trước khi xuất HTML
$showCart = true;
if (isset($_GET['action']) && $_GET['action'] === 'order') {
    if (isset($_SESSION['id_khachhang']) && !empty($_SESSION['cart'])) {
        $id_khachhang = $_SESSION['id_khachhang'];
        $q = mysqli_query($mysqli, "SELECT * FROM table_dangky WHERE id_dangky='$id_khachhang' LIMIT 1");
        $user = mysqli_fetch_assoc($q);
        $ap = $user['ap'] ?? '';
        $xa = $user['xa'] ?? '';
        $tinh = $user['tinh'] ?? '';
        $phone = $user['dienthoai'] ?? '';
        $note = $user['ghichu'] ?? '';
        $address = trim(implode(', ', array_filter([$ap, $xa, $tinh])), ', ');

        $code_order = rand(1000,9999);
        $insert_cart = "INSERT INTO table_giohang (id_khachhang, code_cart, cart_status, ap, xa, tinh, ghichu, dienthoai) VALUES ('$id_khachhang','$code_order',1,'$ap','$xa','$tinh','$note','$phone')";
        mysqli_query($mysqli,$insert_cart);
        $items_html = '<ul class="order-items">';
        foreach($_SESSION['cart'] as $item) {
            $id_pro = isset($item['id']) ? (int)$item['id'] : 0;
            $soluong = isset($item['soluong']) ? (int)$item['soluong'] : 0;
            $giasp = isset($item['giasp']) ? (float)$item['giasp'] : 0;
            $tensp = isset($item['tensanpham']) ? $item['tensanpham'] : '';
            $insert_details = "INSERT INTO table_chitietdonhang (id_sanpham, code_cart, soluongmua) VALUES ('$id_pro','$code_order','$soluong')";
            $ok_details = mysqli_query($mysqli,$insert_details);

            // Giảm số lượng tồn kho sau khi đặt hàng
            if ($ok_details && $id_pro > 0 && $soluong > 0) {
                $update_stock = "UPDATE sanpham SET soluong = CASE WHEN soluong >= $soluong THEN (soluong - $soluong) ELSE 0 END WHERE id_sanpham = $id_pro";
                mysqli_query($mysqli, $update_stock);
            }
            $items_html .= '<li>' . htmlspecialchars($tensp) . ' — Số lượng: ' . (int)$soluong . ' — Giá: ' . number_format($giasp, 0, ',', '.') . "đ</li>";
        }
        $items_html .= '</ul>';

        unset($_SESSION['cart']);
        $customer_name = htmlspecialchars($user['tenkhachhang'] ?? '');
        $customer_phone = htmlspecialchars($user['dienthoai'] ?? '');
        if (trim((string)$address) === '') {
            $safeAddressHtml = '<span class="order-kv-empty">Chưa cập nhật</span>';
        } else {
            $safeAddressHtml = htmlspecialchars((string)$address);
        }
        $safeCode = htmlspecialchars((string)$code_order);

        $_SESSION['order_success'] =
            '<div class="order-success-card">'
                . '<div class="order-success-head">'
                    . '<div class="order-success-title">Đặt hàng thành công</div>'
                    . '<div class="order-success-badge">Mã đơn <strong>' . $safeCode . '</strong></div>'
                . '</div>'
                . '<div class="order-success-body">'
                    . '<div class="order-kv">'
                        . '<div class="order-kv-row"><span class="order-kv-label">Khách hàng</span><span class="order-kv-value">' . $customer_name . '</span></div>'
                        . '<div class="order-kv-row"><span class="order-kv-label">Số điện thoại</span><span class="order-kv-value">' . $customer_phone . '</span></div>'
                        . '<div class="order-kv-row"><span class="order-kv-label">Địa chỉ</span><span class="order-kv-value">' . $safeAddressHtml . '</span></div>'
                    . '</div>'
                    . (trim((string)$address) === '' ? '<div class="order-success-hint">Bạn có thể cập nhật địa chỉ tại <a href="index.php?quanly=thaydoidiachi">Thay đổi địa chỉ</a>.</div>' : '')
                    . '<div class="order-success-section">Chi tiết đơn hàng</div>'
                    . $items_html
                    . '<div class="order-success-actions">'
                        . '<a class="btn-secondary" href="index.php">Tiếp tục mua sắm</a>'
                        . '<a class="btn-primary" href="index.php?quanly=giohang">Xem lại giỏ hàng</a>'
                    . '</div>'
                . '</div>'
            . '</div>';
        header('Location: index.php?quanly=giohang');
        exit();
    } else {
        // nếu chưa đăng nhập hoặc giỏ hàng rỗng sẽ hiển thị thông báo sau
        $_SESSION['order_success'] = 'Bạn cần đăng nhập và giỏ hàng không rỗng.';
        header('Location: index.php?quanly=giohang');
        exit();
    }
}

// hiển thị thông báo thành công nếu có và ẩn giỏ hàng
$orderMsg = '';
if (!empty($_SESSION['order_success'])) {
    $rawMsg = (string)$_SESSION['order_success'];
    $isError = stripos($rawMsg, 'cần đăng nhập') !== false;
    if ($isError) {
        $orderMsg = '<div class="alert alert-error cart-alert">' . $rawMsg . '</div>';
    } else {
        $orderMsg = '<div class="cart-alert">' . $rawMsg . '</div>';
    }
    unset($_SESSION['order_success']);
    $showCart = false;
}
?>

<div class="cart-page">
    <?php
    if ($orderMsg !== '') {
        echo $orderMsg;
    }
    if ($showCart) {
    ?>

    <div class="cart-container">
        <div class="cart-head">
            <div>
                <h2 class="cart-title">Giỏ hàng</h2>
                <div class="cart-subtitle">
                    <?php
                    if (isset($_SESSION['dangky'])) {
                        echo 'Xin chào, <strong>' . htmlspecialchars($_SESSION['dangky']) . '</strong>'; 
                    } else {
                        echo 'Bạn chưa đăng nhập';
                    }
                    ?>
                </div>
            </div>
            <a class="btn-secondary cart-continue" href="index.php">Tiếp tục mua sắm</a>
        </div>

        <div class="table-scroll" role="region" aria-label="Bảng giỏ hàng">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th class="col-id">#</th>
                        <th>Mã SP</th>
                        <th>Tên sản phẩm</th>
                        <th>Hình ảnh</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Thành tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                        $i = 0;
                        $tongtien = 0;
                        foreach ($_SESSION['cart'] as $cart_item) {
                            $thanhtien = $cart_item['soluong'] * $cart_item['giasp'];
                            $tongtien += $thanhtien;
                            $i++;
                    ?>
                    <tr class="cart-item">
                        <td><?php echo $i; ?></td>
                        <td class="cell-code"><?php echo $cart_item['masp']; ?></td>
                        <td class="cell-name"><?php echo $cart_item['tensanpham']; ?></td>
                        <td>
                            <?php
                                $fn = trim($cart_item['hinhanh']);
                                $serverPath = __DIR__ . '/../../admincp/modules/quanlysp/upload/' . $fn;
                                if ($fn !== '' && is_file($serverPath)) {
                                    $src = 'admincp/modules/quanlysp/upload/' . rawurlencode($fn);
                                } else {
                                    $src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
                                }
                            ?>
                            <img class="cart-img" alt="<?php echo htmlspecialchars($cart_item['tensanpham']); ?>" src="<?php echo $src; ?>">
                        </td>
                        <td>
                            <div class="action-links" aria-label="Điều chỉnh số lượng">
                                <a href="pages/main/themgiohang.php?tru=<?php echo $cart_item['id'] ?>" class="btn-subtract" aria-label="Giảm">−</a>
                                <span class="cart-quantity"><?php echo $cart_item['soluong'] ?></span>
                                <a href="pages/main/themgiohang.php?cong=<?php echo $cart_item['id'] ?>" class="btn-add" aria-label="Tăng">+</a>
                            </div>
                        </td>
                        <td class="cart-price"><?php echo number_format($cart_item['giasp'], 0, ',', '.'); ?>đ</td>
                        <td class="cart-total"><?php echo number_format($thanhtien, 0, ',', '.'); ?>đ</td>
                        <td>
                            <a href="pages/main/themgiohang.php?xoa=<?php echo $cart_item['id'] ?>" class="delete-btn">Xóa</a>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr class="cart-summary-row">
                        <td colspan="8">
                            <div class="cart-summary">
                                <div class="cart-summary-left">
                                    <div class="cart-summary-label">Tổng tiền</div>
                                    <div class="cart-summary-value"><?php echo number_format($tongtien, 0, ',', '.') . 'đ' ?></div>
                                </div>
                                <div class="cart-summary-actions">
                                    <a class="btn-danger" href="pages/main/themgiohang.php?xoatatca=1">Xóa tất cả</a>
                                    <?php if (isset($_SESSION['dangky'])) { ?>
                                        <a class="btn-primary" href="?quanly=giohang&action=order">Đặt hàng</a>
                                    <?php } else { ?>
                                        <a class="btn-secondary" href="index.php?quanly=dangky">Đăng ký để đặt hàng</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
                    <?php
                    } else {
                    ?>
                    <tr>
                        <td colspan="8" class="empty-cart">
                            <div class="empty-cart-inner">
                                <div class="empty-cart-title">Giỏ hàng đang trống</div>
                                <div class="empty-cart-sub">Hãy chọn thêm sản phẩm để tiếp tục.</div>
                                <a class="btn-primary" href="index.php">Mua sắm ngay</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
                    <?php
                    }
                    ?>
            </table>
        </div>
    </div>
    <?php } ?>
</div>