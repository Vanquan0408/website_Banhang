<?php

    $sql_danhmuc= "SELECT * FROM  danhmuc ORDER BY id_danhmuc DESC ";
    $query_danhmuc = mysqli_query($mysqli,$sql_danhmuc);

?>
<?php
if(isset($_GET['dangxuat'])&&$_GET['dangxuat']==1){
    unset($_SESSION['dangky']);
}
?>
<div class="menu">
    <div class="container menu-inner">
        <ul class="list_menu">
            <li><a href="index.php">Trang chủ</a></li>
            <?php
            if(isset($_SESSION['dangky'])){
            ?>
                <?php /* Đã chuyển vào menu tài khoản (icon người dùng) trên header */ ?>
            <?php
            }
            ?>
            <li><a href="index.php?quanly=tintuc">Tin tức</a></li>
            <li><a href="index.php?quanly=lienhe">Liên hệ</a></li>
        </ul>

        <form action="index.php?quanly=timkiem" method="POST">
            <input type="text" placeholder="Tìm kiếm sản phẩm..." name="tukhoa">
            <button class="search-btn" type="submit" name="timkiem" value="1">
                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path fill="currentColor" d="M10.5 3a7.5 7.5 0 1 1 4.62 13.41l3.23 3.23a1 1 0 0 1-1.42 1.42l-3.23-3.23A7.5 7.5 0 0 1 10.5 3Zm0 2a5.5 5.5 0 1 0 0 11a5.5 5.5 0 0 0 0-11Z"/>
                </svg>
                <span>Tìm kiếm</span>
            </button>
        </form>
    </div>

    <div class="category-bar">
        <div class="container">
            <div class="category-bar-inner">
                <div class="category-label">Danh mục</div>
                <ul class="category-pills">
                    <?php
                    $activeCategoryId = 0;
                    if (isset($_GET['quanly']) && $_GET['quanly'] === 'danhmucsanpham') {
                        $activeCategoryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                    }
                    ?>
                    <li class="<?php echo ($activeCategoryId === 0) ? 'is-active' : ''; ?>"><a href="index.php">Tất cả</a></li>
                    <?php while($row_danhmuc = mysqli_fetch_assoc($query_danhmuc)) { ?>
                        <li class="<?php echo ($activeCategoryId === (int)$row_danhmuc['id_danhmuc']) ? 'is-active' : ''; ?>">
                            <a href="index.php?quanly=danhmucsanpham&id=<?php echo (int)$row_danhmuc['id_danhmuc'] ?>">
                                <?php echo htmlspecialchars($row_danhmuc['tendanhmuc']); ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    </div>