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
                <p class="price_product">Giá: <?php echo number_format($row['giasp'], 0, ',', '.') . ' VND' ?></p>
                <p style="text-align: center; color: red"><?php echo $row['tendanhmuc'] ?></p>
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
        <li <?php echo ($i == $page) ? 'style="background: brown; color: white;"' : ''; ?>>
            <a href="index.php?trang=<?php echo $i; ?>"><?php echo $i; ?></a>
        </li>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
        <li><a href="index.php?trang=<?php echo $page + 1; ?>">›</a></li>
        <li><a href="index.php?trang=<?php echo $total_pages; ?>">»</a></li>
    <?php endif; ?>
</ul>

<style>
ul.list_trang {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
    padding: 10px;
    list-style: none;
}

ul.list_trang li {
    padding: 8px 12px;
    background: burlywood;
    border-radius: 5px;
    transition: 0.3s;
}

ul.list_trang li a {
    text-decoration: none;
    color: #333;
    font-weight: bold;
}

ul.list_trang li:hover {
    background: chocolate;
    transform: scale(1.1);
}

ul.list_trang li a:hover {
    color: white;
}
</style>
