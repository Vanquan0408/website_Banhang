<?php
include('../../config/config.php');
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['code'])) {
    $code_cart = $_GET['code'];

    $stmt = $mysqli->prepare("DELETE FROM table_chitietdonhang WHERE code_cart=?");
    if ($stmt) {
        $stmt->bind_param("s", $code_cart);
        $stmt->execute();
        $stmt->close();
    }

    $stmt2 = $mysqli->prepare("DELETE FROM table_giohang WHERE code_cart=?");
    if ($stmt2) {
        $stmt2->bind_param("s", $code_cart);
        $stmt2->execute();
        $stmt2->close();
    }

    header('Location: ../../index.php?action=quanlydonhang&query=lietke');
    exit();

} elseif(isset($_GET['code'])) {
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
