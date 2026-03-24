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
                <img src="admincp/modules/quanlysp/upload/<?php echo $row['hinhanh'] ?>" alt="<?php echo $row['tensanpham'] ?>"/>
                <p class="title_product">Tên sản phẩm: <?php echo $row['tensanpham'] ?></p>
                <p class="price_product"><?php echo number_format($row['giasp'], 0, ',', '.') . 'đ' ?></p>
                <p class="category_product"><?php echo $row['tendanhmuc'] ?></p>
            </a>

            <form class="product-add-form js-add-to-cart-form" method="POST" action="pages/main/themgiohang.php">
                <input type="hidden" name="idsanpham" value="<?php echo (int)$row['id_sanpham']; ?>">
                <button class="product-add-btn" type="submit" name="themgiohang" value="1" aria-label="Thêm vào giỏ hàng">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path fill="currentColor" d="M7 18a2 2 0 1 0 0 4a2 2 0 0 0 0-4Zm10 0a2 2 0 1 0 0 4a2 2 0 0 0 0-4ZM6.2 6l.3 2h13.8a1 1 0 0 1 .98 1.2l-1.2 6A2 2 0 0 1 18.14 17H7.2a2 2 0 0 1-1.97-1.65L3.28 3H2a1 1 0 1 1 0-2h2.1a1 1 0 0 1 .98.8L5.6 4H21a1 1 0 1 1 0 2H6.2Z"/>
                        <path fill="currentColor" d="M12 7a1 1 0 0 1 1 1v2h2a1 1 0 1 1 0 2h-2v2a1 1 0 1 1-2 0v-2H9a1 1 0 1 1 0-2h2V8a1 1 0 0 1 1-1Z"/>
                    </svg>
                </button>
            </form>
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
