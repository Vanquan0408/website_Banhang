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

} elseif (isset($_GET['action']) && $_GET['action'] === 'update_order_status' && isset($_GET['code'])) {
    $code_cart = $_GET['code'];
    $order_status = isset($_POST['order_status']) ? (int)$_POST['order_status'] : 1;
    if ($order_status < 1 || $order_status > 6) {
        $order_status = 1;
    }

    // Ensure column exists (best-effort, avoid duplicate-column fatal)
    $hasOrderStatus = false;
    try {
        $qHas = mysqli_query($mysqli, "SHOW COLUMNS FROM table_giohang LIKE 'order_status'");
        if ($qHas && mysqli_num_rows($qHas) > 0) {
            $hasOrderStatus = true;
        }
    } catch (mysqli_sql_exception $e) {
        $hasOrderStatus = false;
    }
    if (!$hasOrderStatus) {
        try {
            mysqli_query($mysqli, "ALTER TABLE table_giohang ADD COLUMN order_status TINYINT NOT NULL DEFAULT 1");
        } catch (mysqli_sql_exception $e) {
            // ignore
        }
    }

    $stmt = $mysqli->prepare("UPDATE table_giohang SET order_status=? WHERE code_cart=?");
    if ($stmt) {
        $stmt->bind_param("is", $order_status, $code_cart);
        if ($stmt->execute()) {
            header('Location: ../../index.php?action=donhang&query=xemdonhang&code=' . urlencode($code_cart));
            exit();
        }
        echo "Lỗi cập nhật trạng thái: " . $stmt->error;
        $stmt->close();
    }

} elseif(isset($_GET['code'])) {
    // Giữ hành vi cũ: đánh dấu đơn đã xem/xác nhận
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
