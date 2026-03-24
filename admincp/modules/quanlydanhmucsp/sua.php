<?php
$sql_sua_danhmucsp = "SELECT * FROM danhmuc WHERE id_danhmuc='$_GET[iddanhmuc]' LIMIT 1";
$query_sua_danhmucsp = mysqli_query($mysqli ,$sql_sua_danhmucsp);
?>
<div class="admin-page-head">
    <div>
        <div class="admin-page-title">Sửa danh mục sản phẩm</div>
        <div class="admin-page-sub">Cập nhật thông tin danh mục</div>
    </div>
    <a class="btn edit-btn" href="index.php?action=quanlydanhmucsanpham&query=them">Quay lại danh sách</a>
</div>

<?php while($dong = mysqli_fetch_array($query_sua_danhmucsp)) { ?>
    <div class="admin-panel admin-panel--form">
        <form method="POST" action="modules/quanlydanhmucsp/xuly.php?iddanhmuc=<?php echo $_GET['iddanhmuc'] ?>" class="styled-form">
            <label class="form-label">Tên danh mục</label>
            <input class="input-field" type="text" name="tendanhmuc" value="<?php echo htmlspecialchars($dong['tendanhmuc']); ?>" required>

            <label class="form-label">Thứ tự</label>
            <input class="input-field" type="number" name="thutu" value="<?php echo (int)$dong['thutu']; ?>" min="0" step="1" required>

            <button class="btn submit-btn" type="submit" name="suadanhmuc" value="1">Lưu thay đổi</button>
        </form>
    </div>
<?php } ?>