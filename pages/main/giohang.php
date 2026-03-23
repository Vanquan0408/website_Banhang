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
        foreach($_SESSION['cart'] as $item) {
            $id_pro = $item['id'];
            $soluong = $item['soluong'];
            $insert_details = "INSERT INTO table_chitietdonhang (id_sanpham, code_cart, soluongmua) VALUES ('$id_pro','$code_order','$soluong')";
            mysqli_query($mysqli,$insert_details);
        }
        unset($_SESSION['cart']);
        $customer_name = htmlspecialchars($user['tenkhachhang']);
        $customer_phone = htmlspecialchars($user['dienthoai']);
        $_SESSION['order_success'] = "Đặt hàng thành công! Mã đơn: $code_order." .
                                  "<br>Tên: $customer_name" .
                                  "<br>SĐT: $customer_phone" .
                                  "<br>Địa chỉ: $address";
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
    $orderMsg = '<div class="alert ' . ($isError ? 'alert-error' : 'alert-success') . ' cart-alert">' . $rawMsg . '</div>';
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
                            <img class="cart-img" alt="<?php echo htmlspecialchars($cart_item['tensanpham']); ?>" src="admincp/modules/quanlysp/upload/<?php echo $cart_item['hinhanh']; ?>">
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