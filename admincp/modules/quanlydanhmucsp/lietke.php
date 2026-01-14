<?php
$sql_lietke_danhmucsp = "SELECT * FROM danhmuc ORDER BY thutu DESC";
$query_lietke_danhmucsp = mysqli_query($mysqli ,$sql_lietke_danhmucsp);
?>
<p class="table-title">Liệt kê danh mục sản phẩm</p>
<table class="styled-table" border="1px">
    <tr>
        <th>Id</th>
        <th>Tên danh mục</th>
        <th>Quản lý</th>
    </tr>
    <?php
    $i=0;
    while($row = mysqli_fetch_array($query_lietke_danhmucsp)){
        $i++;
    ?>
    <tr>
        <td><?php echo $i ?></td>
        <td><?php echo $row['tendanhmuc'] ?></td>
        <td>
            <a href="modules/quanlydanhmucsp/xuly.php?iddanhmuc=<?php echo $row['id_danhmuc']?>" class="btn delete-btn">Xóa</a> 
            | 
            <a href="?action=quanlydanhmucsanpham&query=sua&iddanhmuc=<?php echo $row['id_danhmuc']?>" class="btn edit-btn">Sửa</a>
        </td>
    </tr>
    <?php
    }
    ?>
</table>