<?php
include('../../config/config.php');
$tensanpham = $_POST['tensanpham'];
$masp =  $_POST['masp'];
$giasp = $_POST['giasp'];
$soluong =  $_POST['soluong'];
$hinhanh = $_FILES['hinhanh']['name'];
$hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
$hinhanh = time().'_'.$hinhanh;
$tomtat =  $_POST['tomtat'];
$noidung =  $_POST['noidung'];
$tinhtrang = $_POST['tinhtrang'];
$tdanhmuc = $_POST['tdanhmuc'];

//them
if(isset($_POST['themsanpham'])){
    $sql_them = "INSERT INTO sanpham (tensanpham, masp,giasp,soluong,hinhanh,tomtat,noidung,tinhtrang,id_danhmuc) VALUES ('$tensanpham', '$masp','$giasp','$soluong','$hinhanh','$tomtat','$noidung','$tinhtrang','$tdanhmuc')";
    mysqli_query($mysqli, $sql_them);
    move_uploaded_file($hinhanh_tmp,'upload/'.$hinhanh);
   header('Location:../../index.php?action=quanlysp&query=them');
}elseif (isset($_POST['suasanpham'])){
//sua 
if($hinhanh!=''){
    move_uploaded_file($hinhanh_tmp,'upload/'.$hinhanh);
$sql_update = "UPDATE  sanpham  SET tensanpham='".$tensanpham." ',masp='".$masp."',giasp='".$giasp."',soluong='".$soluong."',hinhanh='".$hinhanh."',tomtat='".$tomtat."',noidung='".$noidung."',tinhtrang='".$tinhtrang."',id_danhmuc='".$tdanhmuc."' WHERE id_sanpham = '$_GET[idsanpham]'";
    $sql ="SELECT * FROM sanpham where id_sanpham='$_GET[idsanpham]' LIMIT 1";
    $query= mysqli_query($mysqli, $sql);
    while ($row=mysqli_fetch_array($query)){
    unlink('upload/'.$row['hinhanh']);
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
mysqli_query($mysqli, $sql_xoa);
header('Location:../../index.php?action=quanlysp&query=them');
}
?>