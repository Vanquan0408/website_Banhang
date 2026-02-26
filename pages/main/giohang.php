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
        $_SESSION['order_success'] = '<span style="color:red;">Bạn cần đăng nhập và giỏ hàng không rỗng.</span>';
        header('Location: index.php?quanly=giohang');
        exit();
    }
}

// hiển thị thông báo thành công nếu có và ẩn giỏ hàng
$orderMsg = '';
if (!empty($_SESSION['order_success'])) {
    $orderMsg = '<p style="color:green;text-align:center;margin:20px 0;">' . $_SESSION['order_success'] . '</p>';
    unset($_SESSION['order_success']);
    $showCart = false;
}
?>
<body>
    <?php
    // nếu có thông báo, hiển thị ngay phía trên giỏ hàng
    if ($orderMsg !== '') {
        echo $orderMsg;
    }
    if ($showCart) {
    ?>
    <div class="cart-container">
        <h2 class="cart-title">Giỏ hàng của
            <?php
            if(isset($_SESSION['dangky'])){
                echo $_SESSION['dangky'];
            }
            ?>
        </h2>
        <table class="cart-table">
            <?php if ($showCart): ?>
            <tr>
                <th>ID</th>
                <th>Mã sản phẩm</th>
                <th>Tên sản phẩm</th>
                <th>Hình ảnh</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Thành tiền</th>
                <th>Hành động</th>
            </tr>
            <?php
            if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
                $i=0;
                $tongtien=0;
                foreach($_SESSION['cart'] as $cart_item){
                    $thanhtien=$cart_item['soluong']*$cart_item['giasp'];
                    $tongtien+=$thanhtien;
                    $i++;
            ?>
            <tr class="cart-item">
                <td><?php echo $i;?></td>
                <td><?php echo $cart_item['masp']?></td>
                <td><?php echo $cart_item['tensanpham']?></td>
                <td><img class="cart-img" src="admincp/modules/quanlysp/upload/<?php echo $cart_item['hinhanh'];?>"></td>
                <td>
                    <div class="action-links">
                        <a href="pages/main/themgiohang.php?cong=<?php echo $cart_item['id']?>" class="btn-add">+</a>
                        <span class="cart-quantity"> <?php echo $cart_item['soluong']?> </span>
                        <a href="pages/main/themgiohang.php?tru=<?php echo $cart_item['id']?>" class="btn-subtract">-</a>
                    </div>
                </td>
                <td class="cart-price"> <?php echo number_format($cart_item['giasp'], 0, ',', '.'); ?> VND</td>
                <td class="cart-total"> <?php echo number_format($thanhtien, 0, ',', '.'); ?> VND</td>
                <td><a href="pages/main/themgiohang.php?xoa=<?php echo $cart_item['id']?>" class="action-links delete-btn">Xóa</a></td>
            </tr>
            <?php
                }
            ?> 
            <tr>
                <td colspan="8">
                <p style="float:left;">Tổng tiền: <?php echo number_format($tongtien,0,',','.').'vnd'?></p>
                <p style="float:right;"><a class="action-links delete-btn" href="pages/main/themgiohang.php?xoatatca=1">Xóa tất cả </a></p>
                <div style="clear: both;"></div>
                <?php
                if(isset($_SESSION['dangky'])){
                ?>
                <p ><a class="action-links delete-btn" href="?quanly=giohang&action=order">Đặt hàng </a></p>
                <?php
                }else{
                ?>
                <p ><a class="action-links delete-btn" href="index.php?quanly=dangky">Đặt ký đặt hàng</a></p>
                <?php
                }
                ?>
                </td>
            </tr>
            <?php
            } else {
            ?>
            <tr>
                <td colspan="8" class="empty-cart">Hiện tại giỏ hàng trống</td>
            </tr>
            <?php
            }
            ?>
            <?php endif; // end showCart ?>
        </table>
    </div>
    <?php } // end if showCart/container ?>
</body>