
</head>
<body>
    <div class="cart-container">
        <h2 class="cart-title">Giỏ hàng của
            <?php
            if(isset($_SESSION['dangky'])){
                echo $_SESSION['dangky'];
            }
            ?>
        </h2>
        <table class="cart-table">
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
                <p ><a class="action-links delete-btn" href="pages/main/thanhtoan.php">Đặt hàng </a></p>
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
        </table>
    </div>
</body>