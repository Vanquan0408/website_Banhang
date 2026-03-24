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
    <?php
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (!empty($_SESSION['upload_error'])) {
        echo '<div class="admin-alert admin-alert--error">' . htmlspecialchars($_SESSION['upload_error']) . '</div>';
        unset($_SESSION['upload_error']);
    }
    if (!empty($_SESSION['flash'])) {
        echo '<div class="admin-alert">' . htmlspecialchars($_SESSION['flash']) . '</div>';
        unset($_SESSION['flash']);
    }
    ?>
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
        <?php $webPath = 'modules/quanlysp/upload/' . rawurlencode($row['hinhanh']); ?>
        <div class="admin-image-field">
            <input id="hinhanh-input-edit" class="form-input-file" type="file" name="hinhanh" accept="image/*,image/webp,image/heic,image/heif">
            <div class="admin-image-preview">
                <img id="hinhanh-preview-edit" src="<?php echo $webPath ?>" width="150px" alt="Ảnh sản phẩm">
                <div class="admin-help">File hiện tại: <?php echo htmlspecialchars($row['hinhanh']); ?></div>
            </div>
        </div>

        <label class="form-label">Tóm tắt</label>
        <textarea id="tomtat" class="form-textarea" rows="6" name="tomtat"><?php echo $row['tomtat']; ?></textarea>

        <label class="form-label">Nội dung</label>
        <textarea id="noidung" class="form-textarea" rows="8" name="noidung"><?php echo $row['noidung']; ?></textarea>

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
<script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
<script>
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('tomtat', { height: 120 });
        CKEDITOR.replace('noidung', { height: 300 });
    }
</script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    var input = document.getElementById('hinhanh-input-edit');
    var preview = document.getElementById('hinhanh-preview-edit');
    if(!input || !preview) return;
    input.addEventListener('change', function(){
        var file = this.files && this.files[0];
        if(!file){
            // if user cleared selection, keep showing existing src
            return;
        }
        if(!file.type.match('image.*')){
            // not an image, do nothing
            return;
        }
        var reader = new FileReader();
        reader.onload = function(e){
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
});
</script>