<?php

$tukhoa = '';
$query_pro = null;

// Support both POST (from the form) and GET (direct link)
if (isset($_POST['timkiem']) || isset($_GET['tukhoa'])) {
    $tukhoa = trim((string)($_POST['tukhoa'] ?? ($_GET['tukhoa'] ?? '')));
    if ($tukhoa !== '') {
        $like = '%' . $tukhoa . '%';

        $sql = "SELECT sanpham.*, danhmuc.tendanhmuc,
                    (SELECT COALESCE(SUM(ct.soluongmua), 0)
                     FROM table_chitietdonhang ct
                     WHERE ct.id_sanpham = sanpham.id_sanpham) AS sold
                FROM sanpham
                JOIN danhmuc ON sanpham.id_danhmuc = danhmuc.id_danhmuc
                WHERE sanpham.tensanpham LIKE ?
                   OR sanpham.masp LIKE ?
                   OR sanpham.tomtat LIKE ?
                   OR danhmuc.tendanhmuc LIKE ?
                ORDER BY sanpham.id_sanpham DESC";

        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('ssss', $like, $like, $like, $like);
            $stmt->execute();
            $query_pro = $stmt->get_result();
            $stmt->close();
        }
    }
}
?>
<h3 class="cart-title">Từ khóa tìm kiếm: <?php echo htmlspecialchars($tukhoa); ?></h3>
<ul class="product_list">
    <?php
    if ($tukhoa !== '' && $query_pro && $query_pro->num_rows > 0) {
        while ($row = $query_pro->fetch_assoc()) {
    ?>
        <li>
            <a href="index.php?quanly=sanpham&id=<?php echo $row['id_sanpham']; ?>">
                <?php
                    $fn = trim($row['hinhanh']);
                    $serverPath = __DIR__ . '/../../admincp/modules/quanlysp/upload/' . $fn;
                    if ($fn !== '' && is_file($serverPath)) {
                        $src = 'admincp/modules/quanlysp/upload/' . rawurlencode($fn);
                    } else {
                        $src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
                    }
                ?>
                <img src="<?php echo $src ?>" alt="<?php echo htmlspecialchars($row['tensanpham']); ?>" />
                <p class="title_product"><?php echo htmlspecialchars($row['tensanpham']); ?></p>
                <p class="sold_product">Đã bán: <?php echo (int)($row['sold'] ?? 0); ?></p>
                <p class="price_product"><?php echo number_format($row['giasp'], 0, ',', '.') . 'đ'; ?></p>
                <p class="category_product"><b><?php echo $row['tendanhmuc']; ?></b></p>
            </a>
        </li>
    <?php
        }
    } else {
        if ($tukhoa === '') {
            echo "<p class='empty-cart'>Vui lòng nhập từ khóa để tìm kiếm.</p>";
        } else {
            echo "<p class='empty-cart'>Không tìm thấy sản phẩm nào!</p>";
        }
    }
    ?>
</ul>
