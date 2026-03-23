<?php
include('../../config/config.php');
$tensanpham = isset($_POST['tensanpham']) ? mysqli_real_escape_string($mysqli, $_POST['tensanpham']) : '';
$masp = isset($_POST['masp']) ? mysqli_real_escape_string($mysqli, $_POST['masp']) : '';
$giasp = isset($_POST['giasp']) ? mysqli_real_escape_string($mysqli, $_POST['giasp']) : '';
$soluong = isset($_POST['soluong']) ? (int)$_POST['soluong'] : 0;
$orig_hinhanh = isset($_FILES['hinhanh']['name']) ? $_FILES['hinhanh']['name'] : '';
$hinhanh_tmp = isset($_FILES['hinhanh']['tmp_name']) ? $_FILES['hinhanh']['tmp_name'] : '';
$hinhanh_error = isset($_FILES['hinhanh']['error']) ? $_FILES['hinhanh']['error'] : 4; // 4 = UPLOAD_ERR_NO_FILE
$hinhanh = '';
if ($orig_hinhanh !== '' && $hinhanh_error === UPLOAD_ERR_OK) {
    $hinhanh = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $orig_hinhanh);
}
$tomtat =  $_POST['tomtat'];
$noidung =  $_POST['noidung'];
$tinhtrang = $_POST['tinhtrang'];
$tdanhmuc = $_POST['tdanhmuc'];

//them
if(isset($_POST['themsanpham'])){
    // If an image was uploaded, move it first
    if($hinhanh !== ''){
        $moved = move_uploaded_file($hinhanh_tmp, __DIR__ . '/upload/' . $hinhanh);
        if(!$moved){
            // don't insert image name if move failed
            $hinhanh = '';
        }
    }
    $sql_them = "INSERT INTO sanpham (tensanpham, masp,giasp,soluong,hinhanh,tomtat,noidung,tinhtrang,id_danhmuc) VALUES ('". $tensanpham ."', '". $masp ."','". $giasp ."','". $soluong ."','". $hinhanh ."','". mysqli_real_escape_string($mysqli, $tomtat) ."','". mysqli_real_escape_string($mysqli, $noidung) ."','". mysqli_real_escape_string($mysqli, $tinhtrang) ."','". mysqli_real_escape_string($mysqli, $tdanhmuc) ."')";
    mysqli_query($mysqli, $sql_them);
    header('Location:../../index.php?action=quanlysp&query=them');
}elseif (isset($_POST['suasanpham'])){
//sua 
if($hinhanh!=''){
    $moved = move_uploaded_file($hinhanh_tmp, __DIR__ . '/upload/' . $hinhanh);
    $sql_update = "UPDATE  sanpham  SET tensanpham='".$tensanpham." ',masp='".$masp."',giasp='".$giasp."',soluong='".$soluong."',hinhanh='".$hinhanh."',tomtat='".$tomtat."',noidung='".$noidung."',tinhtrang='".$tinhtrang."',id_danhmuc='".$tdanhmuc."' WHERE id_sanpham = '$_GET[idsanpham]'";
    $sql ="SELECT * FROM sanpham where id_sanpham='$_GET[idsanpham]' LIMIT 1";
    $query= mysqli_query($mysqli, $sql);
    while ($row=mysqli_fetch_array($query)){
        $old = __DIR__ . '/upload/' . $row['hinhanh'];
        if($row['hinhanh'] && file_exists($old)){
            @unlink($old);
        }
    }
}else{
    $sql_update = "UPDATE  sanpham  SET tensanpham='".$tensanpham." ',masp='".$masp."',giasp='".$giasp."',soluong='".$soluong."',tomtat='".$tomtat."',noidung='".$noidung."',tinhtrang='".$tinhtrang."',id_danhmuc='".$tdanhmuc."' WHERE id_sanpham = '$_GET[idsanpham]'";
}
mysqli_query($mysqli, $sql_update);
header('Location:../../index.php?action=quanlysp&query=them');
}else{
    $id=$_GET['idsanpham'];
    $sql ="SELECT * FROM sanpham where id_sanpham='$id' LIMIT 1";
    $query= mysqli_query($mysqli, $sql);
    while ($row=mysqli_fetch_array($query)){
    unlink('upload/'.$row['hinhanh']);
    }
$sql_xoa = "DELETE FROM sanpham WHERE id_sanpham= '".$id."'";
$result = mysqli_query($mysqli, $sql_xoa);
if(!$result){
    die('MySQL Error: '.mysqli_error($mysqli));
}
header('Location:../../index.php?action=quanlysp&query=them');
}
?>