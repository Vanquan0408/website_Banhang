<p class="cart-title">Chi tiết sản phẩm</P>
<?php
 $id_sanpham = isset($_GET['id']) ? (int)$_GET['id'] : 0;

 $sql_chitiet = "SELECT sp.*, dm.*, COALESCE(sold.sold_qty, 0) AS sold_qty
                FROM sanpham sp
                INNER JOIN danhmuc dm ON sp.id_danhmuc = dm.id_danhmuc
                LEFT JOIN (
                    SELECT id_sanpham, SUM(soluongmua) AS sold_qty
                    FROM table_chitietdonhang
                    GROUP BY id_sanpham
                ) sold ON sold.id_sanpham = sp.id_sanpham
                WHERE sp.id_sanpham = ?
                LIMIT 1";

 $stmt = mysqli_prepare($mysqli, $sql_chitiet);
 mysqli_stmt_bind_param($stmt, 'i', $id_sanpham);
 mysqli_stmt_execute($stmt);
 $query_chitiet = mysqli_stmt_get_result($stmt);
while ($row_chitiet = mysqli_fetch_array($query_chitiet)) {
?>
<div class="product-detail">
    <div class="wrapper_chitiet">
        <div class="hinhanh_sanpham">
            <div class="product-image-card">
                <?php
                    $fn = trim($row_chitiet['hinhanh']);
                    $serverPath = __DIR__ . '/../../admincp/modules/quanlysp/upload/' . $fn;
                    if ($fn !== '' && is_file($serverPath)) {
                        $src = 'admincp/modules/quanlysp/upload/' . rawurlencode($fn);
                    } else {
                        $src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
                    }
                ?>
                <img src="<?php echo $src ?>" alt="<?php echo htmlspecialchars($row_chitiet['tensanpham']); ?>" />
            </div>
        </div>

        <div class="product-right">
            <form method="POST" action="pages/main/themgiohang.php" class="product-form">
                <input type="hidden" name="idsanpham" value="<?php echo $row_chitiet['id_sanpham']; ?>">

                <div class="chitiet_sanpham product-info">
                    <div class="product-badges">
                        <span class="badge badge-category"><?php echo htmlspecialchars($row_chitiet['tendanhmuc']); ?></span>
                        <?php if ((int)$row_chitiet['soluong'] > 0) { ?>
                            <span class="badge badge-stock">Còn <?php echo (int)$row_chitiet['soluong']; ?> sản phẩm</span>
                        <?php } else { ?>
                            <span class="badge badge-stock is-out">Hết hàng</span>
                        <?php } ?>
                        <span class="badge">Đã bán: <?php echo (int)$row_chitiet['sold_qty']; ?></span>
                    </div>

                    <h1 class="product-title"><?php echo htmlspecialchars($row_chitiet['tensanpham']); ?></h1>

                    <div class="product-meta">
                        <div class="meta-row"><span class="meta-label">Mã sản phẩm</span><span class="meta-value"><?php echo htmlspecialchars($row_chitiet['masp']); ?></span></div>
                    </div>

                    <div class="product-price"><?php echo number_format($row_chitiet['giasp'], 0, ',', '.') . 'đ'; ?></div>

                    <div class="product-actions">
                        <button class="themgiohang-btn" type="submit" name="themgiohang" value="1" <?php echo ((int)$row_chitiet['soluong'] <= 0) ? 'disabled' : ''; ?>>
                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path fill="currentColor" d="M7 18a2 2 0 1 0 0 4a2 2 0 0 0 0-4Zm10 0a2 2 0 1 0 0 4a2 2 0 0 0 0-4ZM6.2 6l.3 2h13.8a1 1 0 0 1 .98 1.2l-1.2 6A2 2 0 0 1 18.14 17H7.2a2 2 0 0 1-1.97-1.65L3.28 3H2a1 1 0 1 1 0-2h2.1a1 1 0 0 1 .98.8L5.6 4H21a1 1 0 1 1 0 2H6.2Z"/>
                            </svg>
                            Thêm giỏ hàng
                        </button>
                    </div>

                    <div class="product-desc">
                        <div class="product-desc-title">Mô tả sản phẩm</div>
                        <div class="product-desc-body"><?php echo $row_chitiet['noidung']; ?></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
}
if (isset($stmt) && $stmt) {
    mysqli_stmt_close($stmt);
}
?>
