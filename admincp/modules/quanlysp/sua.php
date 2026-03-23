<?php
$sql_sua_sp = "SELECT * FROM sanpham WHERE id_sanpham='$_GET[idsanpham]' LIMIT 1";
$query_sua_sp = mysqli_query($mysqli ,$sql_sua_sp);
?>
<p class="form-title">Sửa sản phẩm </p>
<div class="form-container">
<table class="styled-form-table" border="1px" width="100%" style="border-collapse: collapse;">
    <?php
    while ($row = mysqli_fetch_array($query_sua_sp)) {
    ?>
<form method="POST" action="modules/quanlysp/xuly.php?idsanpham=<?php echo $row['id_sanpham']?>" enctype="multipart/form-data">
    <tr>
        <td class="form-label">Tên sản phẩm </td>
        <td><input class="form-input" type="text" value ="<?php echo $row['tensanpham']?>" name="tensanpham"></td>
    </tr>
    <tr>
        <td class="form-label">Mã sản phẩm </td>
        <td><input class="form-input" type="text" value ="<?php echo $row['masp']?>"name="masp"></td>
    </tr>
    <tr>
        <td class="form-label">Giá sản phẩm </td>
        <td><input class="form-input"type="text"value ="<?php echo $row['giasp']?>" name="giasp"></td>
    </tr>
    <tr>
        <td class="form-label">Số lượng </td>
        <td><input class="form-input"type="text"value ="<?php echo $row['soluong']?>" name="soluong"></td>
    </tr>
    <tr>
        <td class="form-label">Hình ảnh </td>
        <td >
            <input class="form-input" type="file" name="hinhanh">
            <?php
                $webPath = 'modules/quanlysp/upload/' . $row['hinhanh'];
                $fsPath = __DIR__ . '/upload/' . $row['hinhanh'];
            ?>
            <img src="<?php echo $webPath ?>" width="150px">
            <div style="font-size:12px;color:#666;margin-top:6px;">
                Tên file: <?php echo htmlspecialchars($row['hinhanh']); ?> — Tồn tại trên server: <?php echo file_exists($fsPath) ? 'Có' : 'Không'; ?>
            </div>
        </td>
    </tr>
    <tr>
        <td class="form-label">Tóm tắt </td>
        <td><textarea class="form-input" rows="10" style="resize:none" name="tomtat"><?php echo htmlspecialchars($row['tomtat']); ?></textarea></td>
    </tr>
    <tr>
        <td class="form-label">Nội dung </td>
        <td><textarea class="form-input" rows="10" style="resize:none" name="noidung"><?php echo htmlspecialchars($row['noidung']); ?></textarea></td>
    </tr>
    <tr>
        <td class="form-label">Danh mục sản phẩm </td>
        <td>
            <select class="form-select" name = "tdanhmuc">
                <?php
                $sql_danhmuc= "SELECT * FROM  danhmuc ORDER BY id_danhmuc DESC ";
                $query_danhmuc = mysqli_query($mysqli,$sql_danhmuc);
                while($row_danhmuc = mysqli_fetch_array($query_danhmuc)){
                    if($row_danhmuc['id_danhmuc']==$row['id_danhmuc']){
                ?>
                 <option selected  value="<?php echo $row_danhmuc['id_danhmuc']?>"><?php echo $row_danhmuc['tendanhmuc']?></option>
                <?php
                    }else{
                      ?>
                       <option  value="<?php echo $row_danhmuc['id_danhmuc']?>"><?php echo $row_danhmuc['tendanhmuc']?></option>
                      <?php  
                    }
                }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="form-label">Tình trạng</td>
        <td>
            <select class="form-select" name = "tinhtrang">
                <?php
                if($row['tinhtrang'] == 1) {
                ?>
                <option value="1" selected >Kích hoạt</option>
                <option value="0">Ẩn</option>
                <?php
                } else {
                ?>
                  <option value="1">Kích hoạt</option>
                  <option value="0" selected >Ẩn</option>
                <?php
                }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td  colspan="2"><input class="btn submit-btn" type="submit" name="suasanpham" value="Sửa sản phẩm"></td>
    </tr>
</form>
<?php
    }
?>
</table>
</div>