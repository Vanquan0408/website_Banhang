<?php
$sql_lietke_dh = "SELECT table_giohang.*, 
                table_dangky.tenkhachhang, 
                table_dangky.email, 
                table_dangky.diachi AS default_diachi, 
                table_dangky.dienthoai AS default_dienthoai
            FROM table_giohang
            JOIN table_dangky ON table_giohang.id_khachhang=table_dangky.id_dangky 
            ORDER BY table_giohang.id_cart DESC";
$query_lietke_dh = mysqli_query($mysqli ,$sql_lietke_dh);
?>
<p class="table-title">Quản lý đơn hàng</p>
<div class="table-wrap">
<table class="styled-table" border="1px">
    <tr>
        <th>Id</th>
        <th>Mã đơn hàng </th>
        <th>Tên khách hàng  </th>
        <th>Địa chỉ</th>
        <th>Email  </th>
        <th>Số điện thoại  </th>
        <th>Tình trạng</th>
        <th>Quản lý</th>
    </tr>
    <?php
    $i=0;
    while($row = mysqli_fetch_array($query_lietke_dh)){
        $i++;
    ?>
    <tr>
        <td><?php echo $i ?></td>
        <td><?php echo htmlspecialchars($row['code_cart']) ?></td>
        <td><?php echo htmlspecialchars($row['tenkhachhang']) ?></td>
        <td><?php
            if (!empty($row['ap'])) {
                echo htmlspecialchars($row['ap'] . ', ' . $row['xa'] . ', ' . $row['tinh']);
            } else {
                echo htmlspecialchars($row['default_diachi']);
            }
        ?></td>
        <td><?php echo htmlspecialchars($row['email']) ?></td>
        <td><?php echo htmlspecialchars(!empty($row['dienthoai']) ? $row['dienthoai'] : $row['default_dienthoai']); ?></td>
        <td>
            <?php
            $cancelReq = isset($row['cancel_requested']) ? (int)$row['cancel_requested'] : 0;
            if ($cancelReq === 1) {
                echo '<span class="admin-status admin-status--pending">Yêu cầu hủy</span> ';
            }

            if($row['cart_status']==1){
                echo '<a class="admin-badge admin-badge--new" href="modules/quanlydonhang/xuly.php?code='.htmlspecialchars($row['code_cart']).'">Đơn hàng mới</a>';
            }else{
                echo '<span class="admin-badge admin-badge--seen">Đã xem</span>';
            }
            
            ?>
        </td>
        <td>
            <div class="admin-actions">
                <a href="index.php?action=donhang&query=xemdonhang&code=<?php echo htmlspecialchars($row['code_cart'])?>" class="btn edit-btn">Xem</a>
                <a href="modules/quanlydonhang/xuly.php?action=delete&code=<?php echo htmlspecialchars($row['code_cart'])?>" class="btn delete-btn" onclick="return confirm('Bạn có chắc muốn xóa đơn hàng này?')">Xóa</a>
            </div>

        </td>
    </tr>
    <?php
    }
    ?>
</table>
</div>