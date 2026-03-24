<?php
$id_danhmuc = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$page = isset($_GET['trang']) ? (int)$_GET['trang'] : 1;
$page = max(1, $page); 

$limit = 12;
$begin = ($page - 1) * $limit;


$sql_pro = "SELECT * FROM sanpham 
            INNER JOIN danhmuc ON sanpham.id_danhmuc = danhmuc.id_danhmuc 
            WHERE sanpham.id_danhmuc = $id_danhmuc 
            ORDER BY sanpham.id_sanpham DESC 
            LIMIT $begin, $limit";
$query_pro = mysqli_query($mysqli, $sql_pro);


$sql_cate = "SELECT * FROM danhmuc WHERE id_danhmuc = $id_danhmuc LIMIT 1";
$query_cate = mysqli_query($mysqli, $sql_cate);
$row_title = mysqli_fetch_assoc($query_cate);
?>

<h3 class="cart-title">Danh mục sản phẩm: <?php echo htmlspecialchars($row_title['tendanhmuc'] ?? ''); ?></h3>
<ul class="product_list">
    <?php while ($row_pro = mysqli_fetch_assoc($query_pro)) { ?>
        <li>
            <a href="index.php?quanly=sanpham&id=<?php echo $row_pro['id_sanpham'] ?>">
                <?php
                    $fn = trim($row_pro['hinhanh']);
                    $serverPath = __DIR__ . '/../../admincp/modules/quanlysp/upload/' . $fn;
                    if ($fn !== '' && is_file($serverPath)) {
                        $src = 'admincp/modules/quanlysp/upload/' . rawurlencode($fn);
                    } else {
                        $src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
                    }
                ?>
                <img src="<?php echo $src ?>" alt="<?php echo htmlspecialchars($row_pro['tensanpham']) ?>" />
                <p class="title_product">Tên: <?php echo $row_pro['tensanpham'] ?></p>
                <p class="price_product"><?php echo number_format($row_pro['giasp'], 0, ',', '.') . 'đ' ?></p>
            </a>
        </li>
    <?php } ?>
</ul>

<div style="clear:both;"></div>

<?php
$sql_trang = mysqli_query($mysqli, "SELECT COUNT(id_sanpham) AS total FROM sanpham WHERE id_danhmuc = $id_danhmuc");
$row_trang = mysqli_fetch_assoc($sql_trang);
$total_records = $row_trang['total'];
$total_pages = ceil($total_records / $limit);
?>

<?php if ($total_pages > 1) { ?>
    <?php $baseUrl = 'index.php?quanly=danhmucsanpham&id=' . $id_danhmuc . '&trang='; ?>
    <ul class="list_trang">
        <?php if ($page > 1): ?>
            <li><a href="<?php echo $baseUrl; ?>1">«</a></li>
            <li><a href="<?php echo $baseUrl . ($page - 1); ?>">‹</a></li>
        <?php endif; ?>

        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
            <li class="<?php echo ($i == $page) ? 'is-active' : ''; ?>">
                <a href="<?php echo $baseUrl . $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <li><a href="<?php echo $baseUrl . ($page + 1); ?>">›</a></li>
            <li><a href="<?php echo $baseUrl . $total_pages; ?>">»</a></li>
        <?php endif; ?>
    </ul>
<?php } ?>

