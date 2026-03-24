<?php
include('../../config/config.php');
// sanitize inputs
$tensanpham = isset($_POST['tensanpham']) ? mysqli_real_escape_string($mysqli, $_POST['tensanpham']) : '';
$masp = isset($_POST['masp']) ? mysqli_real_escape_string($mysqli, $_POST['masp']) : '';
$giasp = isset($_POST['giasp']) ? mysqli_real_escape_string($mysqli, $_POST['giasp']) : '';
$soluong = isset($_POST['soluong']) ? (int) $_POST['soluong'] : 0;
$tomtat = isset($_POST['tomtat']) ? $_POST['tomtat'] : '';
$noidung = isset($_POST['noidung']) ? $_POST['noidung'] : '';
$tinhtrang = isset($_POST['tinhtrang']) ? $_POST['tinhtrang'] : 0;
$tdanhmuc = isset($_POST['tdanhmuc']) ? $_POST['tdanhmuc'] : 0;

// file upload handling
$uploadDir = __DIR__ . '/upload/';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0755, true);
}

$hinhanh = '';
if (isset($_FILES['hinhanh']) && isset($_FILES['hinhanh']['error']) && $_FILES['hinhanh']['error'] !== UPLOAD_ERR_NO_FILE) {
    $fileError = $_FILES['hinhanh']['error'];
    if ($fileError === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['hinhanh']['tmp_name'];
        $origName = $_FILES['hinhanh']['name'];

        // validate uploaded file
        if (is_uploaded_file($tmpName)) {
            $info = @getimagesize($tmpName);
            $allowed = ['image/jpeg','image/png','image/gif','image/webp','image/bmp'];
            if ($info && in_array($info['mime'], $allowed, true)) {
                // sanitize filename
                $base = pathinfo($origName, PATHINFO_FILENAME);
                $ext = pathinfo($origName, PATHINFO_EXTENSION);
                $base = preg_replace('/[^A-Za-z0-9_-]/', '_', $base);
                $ext = preg_replace('/[^A-Za-z0-9]/', '', $ext);
                $newName = time() . '_' . $base . ($ext ? '.' . strtolower($ext) : '');
                $target = $uploadDir . $newName;
                // avoid overwrite
                $i = 1;
                while (file_exists($target)) {
                    $newName = time() . '_' . $base . '-' . $i . ($ext ? '.' . strtolower($ext) : '');
                    $target = $uploadDir . $newName;
                    $i++;
                }
                if (move_uploaded_file($tmpName, $target)) {
                    @chmod($target, 0644);
                    $hinhanh = $newName;
                } else {
                    $_SESSION['upload_error'] = 'Không thể lưu file ảnh lên server.';
                }
            } else {
                $_SESSION['upload_error'] = 'Tập tin không phải là ảnh hợp lệ.';
            }
        } else {
            $_SESSION['upload_error'] = 'Không tìm thấy file tạm để upload.';
        }
    } else {
        $_SESSION['upload_error'] = 'Lỗi upload file: ' . $fileError;
    }
}

// them
if (isset($_POST['themsanpham'])) {
    // check upload errors
    if (isset($_SESSION['upload_error'])) {
        $err = $_SESSION['upload_error'];
        unset($_SESSION['upload_error']);
        // still insert without image? we choose to insert and show error
        $_SESSION['flash'] = $err;
    }
    $sql_them = "INSERT INTO sanpham (tensanpham, masp, giasp, soluong, hinhanh, tomtat, noidung, tinhtrang, id_danhmuc) VALUES ('" . mysqli_real_escape_string($mysqli, $tensanpham) . "', '" . mysqli_real_escape_string($mysqli, $masp) . "', '" . mysqli_real_escape_string($mysqli, $giasp) . "', '" . intval($soluong) . "', '" . mysqli_real_escape_string($mysqli, $hinhanh) . "', '" . mysqli_real_escape_string($mysqli, $tomtat) . "', '" . mysqli_real_escape_string($mysqli, $noidung) . "', '" . mysqli_real_escape_string($mysqli, $tinhtrang) . "', '" . mysqli_real_escape_string($mysqli, $tdanhmuc) . "')";
    mysqli_query($mysqli, $sql_them);
    header('Location:../../index.php?action=quanlysp&query=them');
    exit();

} elseif (isset($_POST['suasanpham'])) {
    // sua
    $idsp = isset($_GET['idsanpham']) ? (int) $_GET['idsanpham'] : 0;
    if ($hinhanh !== '') {
        // delete old image
        $sql = "SELECT * FROM sanpham WHERE id_sanpham='$idsp' LIMIT 1";
        $query = mysqli_query($mysqli, $sql);
        while ($row = mysqli_fetch_array($query)) {
            $old = __DIR__ . '/upload/' . $row['hinhanh'];
            if ($row['hinhanh'] && file_exists($old)) {
                @unlink($old);
            }
        }
        $sql_update = "UPDATE sanpham SET tensanpham='" . mysqli_real_escape_string($mysqli, $tensanpham) . "', masp='" . mysqli_real_escape_string($mysqli, $masp) . "', giasp='" . mysqli_real_escape_string($mysqli, $giasp) . "', soluong='" . intval($soluong) . "', hinhanh='" . mysqli_real_escape_string($mysqli, $hinhanh) . "', tomtat='" . mysqli_real_escape_string($mysqli, $tomtat) . "', noidung='" . mysqli_real_escape_string($mysqli, $noidung) . "', tinhtrang='" . mysqli_real_escape_string($mysqli, $tinhtrang) . "', id_danhmuc='" . mysqli_real_escape_string($mysqli, $tdanhmuc) . "' WHERE id_sanpham = '$idsp'";
    } else {
        $sql_update = "UPDATE sanpham SET tensanpham='" . mysqli_real_escape_string($mysqli, $tensanpham) . "', masp='" . mysqli_real_escape_string($mysqli, $masp) . "', giasp='" . mysqli_real_escape_string($mysqli, $giasp) . "', soluong='" . intval($soluong) . "', tomtat='" . mysqli_real_escape_string($mysqli, $tomtat) . "', noidung='" . mysqli_real_escape_string($mysqli, $noidung) . "', tinhtrang='" . mysqli_real_escape_string($mysqli, $tinhtrang) . "', id_danhmuc='" . mysqli_real_escape_string($mysqli, $tdanhmuc) . "' WHERE id_sanpham = '$idsp'";
    }
    mysqli_query($mysqli, $sql_update);
    header('Location:../../index.php?action=quanlysp&query=them');
    exit();

} else {
    // xoa
    $id = isset($_GET['idsanpham']) ? (int) $_GET['idsanpham'] : 0;
    $sql = "SELECT * FROM sanpham WHERE id_sanpham='$id' LIMIT 1";
    $query = mysqli_query($mysqli, $sql);
    while ($row = mysqli_fetch_array($query)) {
        $path = __DIR__ . '/upload/' . $row['hinhanh'];
        if ($row['hinhanh'] && file_exists($path)) {
            @unlink($path);
        }
    }
    $sql_xoa = "DELETE FROM sanpham WHERE id_sanpham= '$id'";
    $result = mysqli_query($mysqli, $sql_xoa);
    if (!$result) {
        die('MySQL Error: ' . mysqli_error($mysqli));
    }
    header('Location:../../index.php?action=quanlysp&query=them');
    exit();
}
?>