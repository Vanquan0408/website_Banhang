<p class="cart-title">Chi tiết của sản phẩm</P>
<?php
 $sql_chitiet= "SELECT * FROM sanpham, danhmuc WHERE sanpham.id_danhmuc = danhmuc.id_danhmuc AND sanpham.id_sanpham = '$_GET[id]' LIMIT 1";
$query_chitiet = mysqli_query($mysqli, $sql_chitiet);
while ($row_chitiet = mysqli_fetch_array($query_chitiet)) {
?>
<div class="wrapper_chitiet">
    <div class="hinhanh_sanpham">
        <img width="100%" src="admincp/modules/quanlysp/upload/<?php echo $row_chitiet['hinhanh']; ?>" />
    </div>
    <form method="POST" action="pages/main/themgiohang.php">
        <input type="hidden" name="idsanpham" value="<?php echo $row_chitiet['id_sanpham']; ?>">
        <div class="chitiet_sanpham">
            <h3>Tên sản phẩm: <?php echo $row_chitiet['tensanpham']; ?></h3>
            <p>Mã sản phẩm: <?php echo $row_chitiet['masp']; ?></p>
            <p>Giá Sản Phẩm: <?php echo number_format($row_chitiet['giasp'], 0, ',', '.') . ' VND'; ?></p>
            <p>Số lượng còn lại: <?php echo $row_chitiet['soluong']; ?></p>
            <p>Danh mục sản phẩm: <?php echo $row_chitiet['tendanhmuc']; ?></p>
            <p>Nội dung sản phẩm: <?php echo $row_chitiet['noidung']; ?></p>
            <p><input class="themgiohang" type="submit" name="themgiohang" value="Thêm giỏ hàng"></p>
        </div>
    </form>
</div>
<?php
}
?>
