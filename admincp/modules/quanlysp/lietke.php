<?php
$sql_lietke_sp = "SELECT * FROM sanpham,danhmuc WHERE sanpham.id_danhmuc = danhmuc.id_danhmuc  ORDER BY id_sanpham DESC";
$query_lietke_sp = mysqli_query($mysqli ,$sql_lietke_sp);
?>
<p class="table-title">Liệt kê sản phẩm</p>
<div class="table-wrap">
<table class="styled-table" border="1px">
<tr>
    <th>Id</th>
    <th>Tên sản phẩm</th>
    <th>Hình ảnh</th>
    <th>Giá sp</th>
    <th>Số lượng</th>
    <th>Danh mục</th>
    <th>Mã sp</th>
    <th style="width: 20%;">Tóm tắt</th> 
    <th>Trạng thái</th>
    <th style="width: 12%;">Quản lý</th>
</tr>

    <?php
    $i=0;
    while($row = mysqli_fetch_array($query_lietke_sp)){
        $i++;
    ?>
    <tr>
        <td><?php echo $i ?></td>
        <td><?php echo htmlspecialchars($row ['tensanpham'])?></td>
        <?php
            $fn = trim($row['hinhanh']);
            $serverPath = __DIR__ . '/upload/' . $fn;
            if ($fn !== '' && is_file($serverPath)) {
                $src = 'modules/quanlysp/upload/' . rawurlencode($fn);
            } else {
                $src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
            }
        ?>
        <td><img src="<?php echo $src ?>" width="150" alt="<?php echo htmlspecialchars($row['tensanpham']) ?>"></td>
        <td><?php echo $row ['giasp']?></td>
        <td><?php echo $row ['soluong']?></td>
        <td><?php echo htmlspecialchars($row ['tendanhmuc'])?></td>
        <td><?php echo htmlspecialchars($row ['masp'])?></td>
        <td class="td-clamp"><?php echo htmlspecialchars($row ['tomtat'])?></td>
        <td>
    <?php
    if($row['tinhtrang'] == 1){
        echo 'Đang kích hoạt';
    } else {
        echo 'Ẩn';
    }
    ?>
</td>

        <td>
            <div class="admin-actions">
                <a class="btn delete-btn" href="modules/quanlysp/xuly.php?idsanpham=<?php echo $row['id_sanpham']?>">Xóa</a>
                <a class="btn edit-btn" href="?action=quanlysp&query=sua&idsanpham=<?php echo $row['id_sanpham']?>">Sửa</a>
            </div>
        </td>
    </tr>
    <?php
    }
    ?>
</table>
</div>