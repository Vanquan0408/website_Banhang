<p class="form-title">Thêm danh mục sản phẩm</p>
<div class="form-container form-container--panel">
    <form method="POST" action="modules/quanlydanhmucsp/xuly.php" class="styled-form">
        <label class="form-label">Tên danh mục</label>
        <input type="text" name="tendanhmuc" class="input-field" placeholder="Nhập tên danh mục..." required>

        <label class="form-label">Thứ tự</label>
        <input type="number" name="thutu" class="input-field" placeholder="VD: 1, 2, 3..." min="0" step="1" required>

        <button type="submit" name="themdanhmuc" class="btn submit-btn">Thêm danh mục</button>
    </form>
</div>
