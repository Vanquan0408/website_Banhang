<?php
if(isset($_POST['timkiem'])){
    $tukhoa = mysqli_real_escape_string($mysqli, $_POST['tukhoa']);
    $sql_pro = "SELECT * FROM sanpham 
                JOIN danhmuc ON sanpham.id_danhmuc = danhmuc.id_danhmuc 
                WHERE sanpham.tensanpham LIKE '%$tukhoa%' 
                OR sanpham.tomtat LIKE '%$tukhoa%' 
                ORDER BY sanpham.id_sanpham DESC";
    $query_pro = mysqli_query($mysqli, $sql_pro);
}
?>
<h3 class="cart-title">Từ khóa tìm kiếm: <?php echo htmlspecialchars($tukhoa); ?></h3>
<ul class="product_list">
    <?php
    if(isset($query_pro) && mysqli_num_rows($query_pro) > 0){
        while($row = mysqli_fetch_array($query_pro)){
    ?>
        <li>
            <a href="index.php?quanly=sanpham&id=<?php echo $row['id_sanpham']; ?>">
                <img src="admincp/modules/quanlysp/upload/<?php echo $row['hinhanh']; ?>" />
                <p class="title_product"><b>Tên sản phẩm:</b> <?php echo $row['tensanpham']; ?></p>
                <p class="price_product"><b>Giá:</b> <?php echo number_format($row['giasp'], 0, ',', '.') . ' VND'; ?></p>
                <p style="text-align: center; color: red;"><b><?php echo $row['tendanhmuc']; ?></b></p>
            </a>
        </li>
    <?php
        }
    } else {
        echo "<p style='color:red; text-align:center; font-weight:bold;'>Không tìm thấy sản phẩm nào!</p>";
    }
    ?>
</ul>
