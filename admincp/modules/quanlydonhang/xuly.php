<?php
include('../../config/config.php');

if(isset($_GET['code'])) {
    $code_cart = $_GET['code'];
    $stmt = $mysqli->prepare("UPDATE table_giohang SET cart_status=0 WHERE code_cart=?");
    $stmt->bind_param("s", $code_cart);
    if($stmt->execute()) {
        header('Location: ../../index.php?action=quanlydonhang&query=lietke');
        exit();
    } else {
        echo "Lỗi cập nhật đơn hàng: " . $stmt->error;
    }
}
?>
