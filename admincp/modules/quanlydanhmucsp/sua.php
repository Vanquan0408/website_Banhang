<?php
$sql_sua_danhmucsp = "SELECT * FROM danhmuc WHERE id_danhmuc='$_GET[iddanhmuc]' LIMIT 1";
$query_sua_danhmucsp = mysqli_query($mysqli ,$sql_sua_danhmucsp);
?>
<p class="form-title">Sửa thông tin sản phẩm </p>
<table class="styled-table" border="1px" width="50%" style="border-collapse: collapse;">
<form method="POST" action="modules/quanlydanhmucsp/xuly.php?iddanhmuc=<?php echo $_GET['iddanhmuc'] ?>">
    <?php
    while($dong = mysqli_fetch_array($query_sua_danhmucsp)){

    
    ?>
    <tr>
        <td>Tên danh mục</td>
        <td><input  class="form-input" type="text" value=<?php echo $dong['tendanhmuc']?> name="tendanhmuc"></td>
    </tr>
    <tr>
        <td>Thứ tự</td>
        <td><input  class="form-input" type="text" value=<?php echo $dong['thutu']?> name="thutu"></td>
    </tr>
    <tr>
        <td colspan="2"><input class="submit-btn" type="submit" name="suadanhmuc" value="Sửa danh mục sản phẩm"></td>
    </tr>
    <?php
    }
    ?>
</form>
</table>