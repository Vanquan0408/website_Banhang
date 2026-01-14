<?php
$sql_lietke_dh = "SELECT * FROM table_giohang,table_dangky 
 WHERE table_giohang.id_khachhang=table_dangky.id_dangky 
 ORDER BY table_giohang.id_cart DESC";
$query_lietke_dh = mysqli_query($mysqli ,$sql_lietke_dh);
?>
<p class="table-title">Liệt kê đơn hàng </p>
<table class="styled-table" border="1px">
    <tr>
        <th>Id</th>
        <th>Mã đơn hàng </th>
        <th>Tên khách hàng  </th>
        <th>Đại chỉ  </th>
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
        <td><?php echo $row['code_cart'] ?></td>
        <td><?php echo $row['tenkhachhang'] ?></td>
        <td><?php echo $row['diachi'] ?></td>
        <td><?php echo $row['email'] ?></td>
        <td><?php echo $row['dienthoai'] ?></td>
        <td>
            <?php
            if($row['cart_status']==1){
                echo '<a class="btn delete-btn" href="modules/quanlydonhang/xuly.php?code='.$row['code_cart'].'"> Đơn hàng mới  </a>';
            }else{
                echo '<a class="btn delete-btn">Đã xem</a>';
            }
            
            ?>
        </td>
        <td>
            <a href="index.php?action=donhang&query=xemdonhang&code=<?php echo $row['code_cart']?>" class="btn delete-btn">Xem đơn hàng</a> 

        </td>
    </tr>
    <?php
    }
    ?>
</table>