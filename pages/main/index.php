<h3 class="cart-title"> Sản phẩm mới nhất </h3>
<?php

$page = isset($_GET['trang']) ? (int)$_GET['trang'] : 1;
$page = max(1, $page); 

$limit = 12;
$begin = ($page - 1) * $limit;


$sql_pro = "SELECT * FROM sanpham INNER JOIN danhmuc ON sanpham.id_danhmuc = danhmuc.id_danhmuc 
            ORDER BY sanpham.id_sanpham DESC LIMIT $begin, $limit";
$query_pro = mysqli_query($mysqli, $sql_pro);
?>
<ul class="product_list">
    <?php while ($row = mysqli_fetch_array($query_pro)) { ?>
        <li>
            <a href="index.php?quanly=sanpham&id=<?php echo $row['id_sanpham'] ?>">
                <?php
                    $fn = trim($row['hinhanh']);
                    $serverPath = __DIR__ . '/../../admincp/modules/quanlysp/upload/' . $fn;
                    if ($fn !== '' && is_file($serverPath)) {
                        $src = 'admincp/modules/quanlysp/upload/' . rawurlencode($fn);
                    } else {
                        $src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
                    }
                ?>
                <img src="<?php echo $src ?>" alt="<?php echo htmlspecialchars($row['tensanpham']) ?>" />
                <p class="title_product">Tên sản phẩm: <?php echo $row['tensanpham'] ?></p>
                <p class="price_product"><?php echo number_format($row['giasp'], 0, ',', '.') . 'đ' ?></p>
                <p class="category_product"><?php echo $row['tendanhmuc'] ?></p>
            </a>
        </li>
    <?php } ?>
</ul>

<?php
$sql_trang = mysqli_query($mysqli, "SELECT COUNT(id_sanpham) AS total FROM sanpham");
$row_trang = mysqli_fetch_assoc($sql_trang);
$total_records = $row_trang['total'];
$total_pages = ceil($total_records / $limit);
?>

<ul class="list_trang">
    <?php if ($page > 1): ?>
        <li><a href="index.php?trang=1">«</a></li>
        <li><a href="index.php?trang=<?php echo $page - 1; ?>">‹</a></li>
    <?php endif; ?>

    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
        <li class="<?php echo ($i == $page) ? 'is-active' : ''; ?>">
            <a href="index.php?trang=<?php echo $i; ?>"><?php echo $i; ?></a>
        </li>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
        <li><a href="index.php?trang=<?php echo $page + 1; ?>">›</a></li>
        <li><a href="index.php?trang=<?php echo $total_pages; ?>">»</a></li>
    <?php endif; ?>
</ul>
