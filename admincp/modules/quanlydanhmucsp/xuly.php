<?php
session_start();
if (!isset($_SESSION['dangnhap'])) {
    header('Location: ../../login.php');
    exit();
}
include('../../config/config.php');
$tenloaisp = mysqli_real_escape_string($mysqli, $_POST['tendanhmuc']);
$thutu = (int) $_POST['thutu'];
//them
if(isset($_POST['themdanhmuc'])){
    $sql_them = "INSERT INTO danhmuc (tendanhmuc, thutu) VALUES ('$tenloaisp', '$thutu')";
    mysqli_query($mysqli, $sql_them);
    header('Location:../../index.php?action=quanlydanhmucsanpham&query=them');
}elseif (isset($_POST['suadanhmuc'])){
//sua 
$sql_update = "UPDATE  danhmuc  SET tendanhmuc='".$tenloaisp." ',thutu='".$thutu."' WHERE id_danhmuc = '$_GET[iddanhmuc]'";
mysqli_query($mysqli, $sql_update);
header('Location:../../index.php?action=quanlydanhmucsanpham&query=them');
} else {
    $id = isset($_GET['iddanhmuc']) ? (int)$_GET['iddanhmuc'] : 0;

    // Kiểm tra có sản phẩm nào thuộc danh mục này hay không
    $check_sql = "SELECT COUNT(id_sanpham) AS cnt FROM sanpham WHERE id_danhmuc = $id";
    $check_res = mysqli_query($mysqli, $check_sql);
    $row_cnt = mysqli_fetch_assoc($check_res);
    $count = isset($row_cnt['cnt']) ? (int)$row_cnt['cnt'] : 0;

    if ($count > 0) {
        echo "<script>alert('Không thể xóa danh mục: còn " . $count . " sản phẩm thuộc danh mục này. Vui lòng xóa hoặc chuyển danh mục các sản phẩm trước.');window.location='../../index.php?action=quanlydanhmucsanpham&query=them';</script>";
        exit();
    }

    $sql_xoa = "DELETE FROM danhmuc WHERE id_danhmuc = $id";
    $result = mysqli_query($mysqli, $sql_xoa);
    if (!$result) {
        die('MySQL Error: '.mysqli_error($mysqli));
    }
    header('Location: ../../index.php?action=quanlydanhmucsanpham&query=them');
}
?>