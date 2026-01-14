<p class="form-title">Thêm sản phẩm</p>
<div class="form-container">
    <table class="styled-form-table">
        <form method="POST" action="modules/quanlysp/xuly.php" enctype="multipart/form-data">
            <tr>
                <td class="form-label">Tên sản phẩm</td>
                <td><input type="text" name="tensanpham" class="form-input"></td>
            </tr>
            <tr>
                <td class="form-label">Mã sản phẩm</td>
                <td><input type="text" name="masp" class="form-input"></td>
            </tr>
            <tr>
                <td class="form-label">Giá sản phẩm</td>
                <td><input type="text" name="giasp" class="form-input"></td>
            </tr>
            <tr>
                <td class="form-label">Số lượng</td>
                <td><input type="text" name="soluong" class="form-input"></td>
            </tr>
            <tr>
                <td class="form-label">Hình ảnh</td>
                <td><input type="file" name="hinhanh" class="form-input-file"></td>
            </tr>
            <tr>
                <td class="form-label">Tóm tắt</td>
                <td><textarea rows="5" class="form-textarea" name="tomtat"></textarea></td>
            </tr>
            <tr>
                <td class="form-label">Nội dung</td>
                <td><textarea rows="5" class="form-textarea" name="noidung"></textarea></td>
            </tr>
            <tr>
                <td class="form-label">Danh mục sản phẩm</td>
                <td>
                    <select name="tdanhmuc" class="form-select">
                        <?php
                        $sql_danhmuc = "SELECT * FROM danhmuc ORDER BY id_danhmuc DESC";
                        $query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);
                        while ($row_danhmuc = mysqli_fetch_array($query_danhmuc)) {
                        ?>
                            <option value="<?php echo $row_danhmuc['id_danhmuc'] ?>">
                                <?php echo $row_danhmuc['tendanhmuc'] ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="form-label">Tình trạng</td>
                <td>
                    <select name="tinhtrang" class="form-select">
                        <option value="1">Kích hoạt</option>
                        <option value="0">Ẩn</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" name="themsanpham" value="Thêm sản phẩm" class="btn submit-btn">
                </td>
            </tr>
        </form>
    </table>
</div>
