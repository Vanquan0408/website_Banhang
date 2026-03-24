<p class="form-title">Thêm sản phẩm</p>
<div class="form-container form-container--panel">
    <form method="POST" action="modules/quanlysp/xuly.php" enctype="multipart/form-data" class="styled-form admin-product-form">
        <div class="admin-form-grid">
            <div class="span-2">
                <label class="form-label">Tên sản phẩm</label>
                <input type="text" name="tensanpham" class="input-field" placeholder="Nhập tên sản phẩm..." required>
            </div>

            <div>
                <label class="form-label">Mã sản phẩm</label>
                <input type="text" name="masp" class="input-field" placeholder="VD: SP001" required>
            </div>

            <div>
                <label class="form-label">Danh mục sản phẩm</label>
                <select name="tdanhmuc" class="form-select" required>
                    <?php
                    $sql_danhmuc = "SELECT * FROM danhmuc ORDER BY id_danhmuc DESC";
                    $query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);
                    while ($row_danhmuc = mysqli_fetch_array($query_danhmuc)) {
                    ?>
                        <option value="<?php echo $row_danhmuc['id_danhmuc'] ?>">
                            <?php echo htmlspecialchars($row_danhmuc['tendanhmuc']) ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>

            <div>
                <label class="form-label">Giá sản phẩm</label>
                <input type="number" name="giasp" class="input-field" min="0" step="1" placeholder="VD: 100000" required>
            </div>

            <div>
                <label class="form-label">Số lượng</label>
                <input type="number" name="soluong" class="input-field" min="0" step="1" placeholder="VD: 10" required>
            </div>

            <div>
                <label class="form-label">Tình trạng</label>
                <select name="tinhtrang" class="form-select" required>
                    <option value="1">Kích hoạt</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>

            <div>
                <label class="form-label">Hình ảnh</label>
                <input type="file" name="hinhanh" class="form-input-file" accept="image/*" required>
            </div>

            <div class="span-2">
                <label class="form-label">Tóm tắt</label>
                <textarea rows="4" class="form-textarea" name="tomtat" placeholder="Mô tả ngắn..."></textarea>
            </div>

            <div class="span-2">
                <label class="form-label">Nội dung</label>
                <textarea rows="6" class="form-textarea" name="noidung" placeholder="Mô tả chi tiết..."></textarea>
            </div>

            <div class="span-2">
                <button type="submit" name="themsanpham" value="1" class="btn submit-btn">Thêm sản phẩm</button>
            </div>
        </div>
    </form>
</div>
