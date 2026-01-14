<?php
$id_danhmuc = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$page = isset($_GET['trang']) ? (int)$_GET['trang'] : 1;
$page = max(1, $page); 

$limit = 10000; 
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

<h3 class="cart-title">Danh mục sản phẩm: <?php echo $row_title['tendanhmuc'] ?></h3>
<ul class="product_list">
    <?php while ($row_pro = mysqli_fetch_assoc($query_pro)) { ?>
        <li>
            <a href="index.php?quanly=sanpham&id=<?php echo $row_pro['id_sanpham'] ?>">
                <img src="admincp/modules/quanlysp/upload/<?php echo $row_pro['hinhanh'] ?>" />
                <p class="title_product">Tên: <?php echo $row_pro['tensanpham'] ?></p>
                <p class="price_product">Giá: <?php echo number_format($row_pro['giasp'], 0, ',', '.') . ' VND' ?></p>
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
