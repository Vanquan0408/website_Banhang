<?php
$sql_sua_sp = "SELECT * FROM sanpham WHERE id_sanpham='$_GET[idsanpham]' LIMIT 1";
$query_sua_sp = mysqli_query($mysqli ,$sql_sua_sp);
?>
<div class="admin-page-head">
    <div>
        <div class="admin-page-title">Sửa sản phẩm</div>
        <div class="admin-page-sub">Cập nhật thông tin sản phẩm</div>
    </div>
    <a class="btn edit-btn" href="index.php?action=quanlysp&query=them">Quay lại danh sách</a>
</div>

<?php while ($row = mysqli_fetch_array($query_sua_sp)) { ?>
<div class="admin-panel admin-panel--form">
    <form method="POST" action="modules/quanlysp/xuly.php?idsanpham=<?php echo $row['id_sanpham']?>" enctype="multipart/form-data" class="styled-form">
        <label class="form-label">Tên sản phẩm</label>
        <input class="input-field" type="text" value="<?php echo htmlspecialchars($row['tensanpham']); ?>" name="tensanpham" required>

        <label class="form-label">Mã sản phẩm</label>
        <input class="input-field" type="text" value="<?php echo htmlspecialchars($row['masp']); ?>" name="masp" required>

        <div class="admin-form-grid">
            <div>
                <label class="form-label">Giá sản phẩm</label>
                <input class="input-field" type="number" value="<?php echo (int)$row['giasp']; ?>" name="giasp" min="0" step="1" required>
            </div>
            <div>
                <label class="form-label">Số lượng</label>
                <input class="input-field" type="number" value="<?php echo (int)$row['soluong']; ?>" name="soluong" min="0" step="1" required>
            </div>
        </div>

        <label class="form-label">Hình ảnh</label>
        <?php $webPath = 'modules/quanlysp/upload/' . $row['hinhanh']; ?>
        <div class="admin-image-field">
            <input class="form-input-file" type="file" name="hinhanh" accept="image/*">
            <div class="admin-image-preview">
                <img src="<?php echo $webPath ?>" width="150px" alt="Ảnh sản phẩm">
                <div class="admin-help">File hiện tại: <?php echo htmlspecialchars($row['hinhanh']); ?></div>
            </div>
        </div>

        <label class="form-label">Tóm tắt</label>
        <textarea class="form-textarea" rows="6" name="tomtat"><?php echo htmlspecialchars($row['tomtat']); ?></textarea>

        <label class="form-label">Nội dung</label>
        <textarea class="form-textarea" rows="8" name="noidung"><?php echo htmlspecialchars($row['noidung']); ?></textarea>

        <label class="form-label">Danh mục sản phẩm</label>
        <select class="form-select" name="tdanhmuc" required>
            <?php
            $sql_danhmuc= "SELECT * FROM  danhmuc ORDER BY id_danhmuc DESC ";
            $query_danhmuc = mysqli_query($mysqli,$sql_danhmuc);
            while($row_danhmuc = mysqli_fetch_array($query_danhmuc)){
                $selected = ($row_danhmuc['id_danhmuc']==$row['id_danhmuc']) ? 'selected' : '';
            ?>
                <option <?php echo $selected; ?> value="<?php echo $row_danhmuc['id_danhmuc']?>"><?php echo htmlspecialchars($row_danhmuc['tendanhmuc'])?></option>
            <?php
            }
            ?>
        </select>

        <label class="form-label">Tình trạng</label>
        <select class="form-select" name="tinhtrang" required>
            <option value="1" <?php echo ($row['tinhtrang'] == 1) ? 'selected' : ''; ?>>Kích hoạt</option>
            <option value="0" <?php echo ($row['tinhtrang'] == 0) ? 'selected' : ''; ?>>Ẩn</option>
        </select>

        <button class="btn submit-btn" type="submit" name="suasanpham" value="1">Lưu thay đổi</button>
    </form>
</div>
<?php } ?>