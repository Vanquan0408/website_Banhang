<?php
// Trang "Đơn mua" (lịch sử đơn hàng của khách)
// URL: index.php?quanly=donmua hoặc index.php?quanly=donmua&code=XXXX

$customerId = isset($_SESSION['id_khachhang']) ? (int)$_SESSION['id_khachhang'] : 0;
$code = isset($_GET['code']) ? trim((string)$_GET['code']) : '';
$code = preg_replace('/[^0-9]/', '', $code);

$hasOrderStatus = false;
try {
    $qHas = mysqli_query($mysqli, "SHOW COLUMNS FROM table_giohang LIKE 'order_status'");
    if ($qHas && mysqli_num_rows($qHas) > 0) {
        $hasOrderStatus = true;
    }
} catch (mysqli_sql_exception $e) {
    $hasOrderStatus = false;
}

// Best-effort DB migration: only add column if missing
if (!$hasOrderStatus) {
    try {
        mysqli_query($mysqli, "ALTER TABLE table_giohang ADD COLUMN order_status TINYINT NOT NULL DEFAULT 1");
        $hasOrderStatus = true;
    } catch (mysqli_sql_exception $e) {
        // ignore (no permission / already exists / etc.)
        $hasOrderStatus = false;
    }
}

$statusTabs = [
    ''  => 'Tất cả',
    '1' => 'Chờ thanh toán',
    '2' => 'Vận chuyển',
    '3' => 'Chờ giao hàng',
    '4' => 'Hoàn thành',
    '5' => 'Đã hủy',
    '6' => 'Trả hàng/Hoàn tiền',
];

$st = isset($_GET['st']) ? (string)$_GET['st'] : '';
if (!array_key_exists($st, $statusTabs)) {
    $st = '';
}

$flashHtml = '';

function buildFlash($type, $message)
{
    $cls = ($type === 'error') ? 'alert alert-error cart-alert' : 'alert alert-success cart-alert';
    return '<div class="' . $cls . '">' . htmlspecialchars($message) . '</div>';
}

function renderStatusBadge($orderStatus, $fallbackCartStatus = null)
{
    $st = (int)$orderStatus;
    switch ($st) {
        case 1:
            return '<span class="status-badge status-badge--payment">Chờ thanh toán</span>';
        case 2:
            return '<span class="status-badge status-badge--shipping">Vận chuyển</span>';
        case 3:
            return '<span class="status-badge status-badge--delivering">Chờ giao hàng</span>';
        case 4:
            return '<span class="status-badge status-badge--completed">Hoàn thành</span>';
        case 5:
            return '<span class="status-badge status-badge--canceled">Đã hủy</span>';
        case 6:
            return '<span class="status-badge status-badge--refund">Trả hàng/Hoàn tiền</span>';
        default:
            // Fallback: if old system only has cart_status (0/1)
            $fallback = (int)$fallbackCartStatus;
            if ($fallback === 1) {
                return '<span class="status-badge status-badge--pending">Đơn mới</span>';
            }
            return '<span class="status-badge status-badge--confirmed">Đã xác nhận</span>';
    }
}
?>

<div class="purchase-page">
    <div class="purchase-container">
        <div class="purchase-head">
            <div>
                <h2 class="purchase-title">Đơn mua</h2>
                <div class="purchase-sub">Theo dõi tình trạng đơn hàng và xem lại các đơn đã mua.</div>
            </div>
            <a class="btn-secondary" href="index.php">Tiếp tục mua sắm</a>
        </div>

        <div class="cart-tabs" role="tablist" aria-label="Điều hướng">
            <a class="cart-tab" href="index.php?quanly=giohang" role="tab">Giỏ hàng</a>
            <a class="cart-tab is-active" href="index.php?quanly=donmua" role="tab" aria-selected="true">Đơn mua</a>
        </div>

        <div class="purchase-tabs" role="tablist" aria-label="Trạng thái đơn mua">
            <?php foreach ($statusTabs as $key => $label) {
                $active = ($st === $key);
                $href = 'index.php?quanly=donmua' . ($key !== '' ? ('&st=' . urlencode($key)) : '');
            ?>
                <a class="purchase-tab<?php echo $active ? ' is-active' : ''; ?>" href="<?php echo $href; ?>" role="tab" <?php echo $active ? 'aria-selected="true"' : ''; ?>>
                    <?php echo htmlspecialchars($label); ?>
                </a>
            <?php } ?>
        </div>

        <?php if ($customerId <= 0) { ?>
            <div class="alert alert-error cart-alert">
                Bạn cần đăng nhập để xem đơn mua.
                <div class="purchase-actions">
                    <a class="btn-primary" href="index.php?quanly=dangnhap">Đăng nhập</a>
                    <a class="btn-secondary" href="index.php?quanly=dangky">Đăng ký</a>
                </div>
            </div>
        <?php } else { ?>

            <?php
            // Handle actions (cancel / rebuy)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $action = isset($_POST['purchase_action']) ? (string)$_POST['purchase_action'] : '';
                $postCode = isset($_POST['code']) ? preg_replace('/[^0-9]/', '', (string)$_POST['code']) : '';

                if ($postCode === '') {
                    $flashHtml = buildFlash('error', 'Mã đơn không hợp lệ.');
                } else {
                    $codeSafe = mysqli_real_escape_string($mysqli, $postCode);
                    $qOrder = mysqli_query($mysqli, "SELECT * FROM table_giohang WHERE id_khachhang = $customerId AND code_cart = '$codeSafe' LIMIT 1");
                    $order = $qOrder ? mysqli_fetch_assoc($qOrder) : null;

                    if (!$order) {
                        $flashHtml = buildFlash('error', 'Không tìm thấy đơn hàng hoặc bạn không có quyền thao tác.');
                    } else {
                        $orderStatus = $hasOrderStatus ? (int)($order['order_status'] ?? 1) : 0;

                        if ($action === 'cancel') {
                            if (!$hasOrderStatus) {
                                $flashHtml = buildFlash('error', 'Hệ thống chưa hỗ trợ hủy đơn (thiếu trạng thái đơn hàng).');
                            } elseif (in_array($orderStatus, [1, 2, 3], true)) {
                                $stmt = $mysqli->prepare("UPDATE table_giohang SET order_status=5 WHERE id_khachhang=? AND code_cart=? AND order_status IN (1,2,3)");
                                if ($stmt) {
                                    $stmt->bind_param('is', $customerId, $postCode);
                                    $stmt->execute();
                                    $affected = $stmt->affected_rows;
                                    $stmt->close();
                                    if ($affected > 0) {
                                        $flashHtml = buildFlash('success', 'Đã hủy đơn hàng thành công.');
                                    } else {
                                        $flashHtml = buildFlash('error', 'Không thể hủy đơn ở trạng thái hiện tại.');
                                    }
                                } else {
                                    $flashHtml = buildFlash('error', 'Không thể hủy đơn hàng.');
                                }
                            } else {
                                $flashHtml = buildFlash('error', 'Không thể hủy đơn ở trạng thái hiện tại.');
                            }
                        } elseif ($action === 'rebuy') {
                            // Only allow repurchase for completed or canceled
                            if (!$hasOrderStatus || in_array($orderStatus, [4, 5], true)) {
                                $itemsSql = "SELECT sanpham.id_sanpham, sanpham.tensanpham, sanpham.giasp, sanpham.hinhanh, sanpham.masp, table_chitietdonhang.soluongmua
                                            FROM table_chitietdonhang
                                            JOIN sanpham ON table_chitietdonhang.id_sanpham = sanpham.id_sanpham
                                            WHERE table_chitietdonhang.code_cart = '$codeSafe'
                                            ORDER BY table_chitietdonhang.id_cart_details DESC";
                                $qItems = mysqli_query($mysqli, $itemsSql);
                                if (!$qItems || mysqli_num_rows($qItems) === 0) {
                                    $flashHtml = buildFlash('error', 'Đơn hàng không có sản phẩm để mua lại.');
                                } else {
                                    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
                                        $_SESSION['cart'] = [];
                                    }

                                    while ($row = mysqli_fetch_assoc($qItems)) {
                                        $pid = (int)($row['id_sanpham'] ?? 0);
                                        $qty = (int)($row['soluongmua'] ?? 0);
                                        if ($pid <= 0 || $qty <= 0) continue;

                                        $newProduct = [
                                            'id' => (string)$pid,
                                            'tensanpham' => (string)($row['tensanpham'] ?? ''),
                                            'soluong' => $qty,
                                            'giasp' => (float)($row['giasp'] ?? 0),
                                            'hinhanh' => (string)($row['hinhanh'] ?? ''),
                                            'masp' => (string)($row['masp'] ?? ''),
                                        ];

                                        $found = false;
                                        foreach ($_SESSION['cart'] as &$cartItem) {
                                            if (isset($cartItem['id']) && (string)$cartItem['id'] === (string)$pid) {
                                                $cartItem['soluong'] = (int)($cartItem['soluong'] ?? 0) + $qty;
                                                $found = true;
                                                break;
                                            }
                                        }
                                        unset($cartItem);

                                        if (!$found) {
                                            $_SESSION['cart'][] = $newProduct;
                                        }
                                    }

                                    $_SESSION['cart_flash'] = 'Đã thêm sản phẩm của đơn vào giỏ hàng. Bạn có thể chỉnh số lượng và đặt lại.';
                                    header('Location: index.php?quanly=giohang');
                                    exit();
                                }
                            } else {
                                $flashHtml = buildFlash('error', 'Chỉ có thể mua lại khi đơn Hoàn thành hoặc Đã hủy.');
                            }
                        }
                    }
                }
            }
            ?>

            <?php if ($flashHtml !== '') { echo $flashHtml; } ?>

            <?php
            // Lấy thông tin mặc định của khách (fallback cho địa chỉ/sđt)
            $qUser = mysqli_query($mysqli, "SELECT tenkhachhang, diachi, dienthoai FROM table_dangky WHERE id_dangky = $customerId LIMIT 1");
            $user = $qUser ? mysqli_fetch_assoc($qUser) : null;
            $defaultAddress = $user['diachi'] ?? '';
            $defaultPhone = $user['dienthoai'] ?? '';

            if ($code !== '') {
                $codeSafe = mysqli_real_escape_string($mysqli, $code);

                $qOrder = mysqli_query(
                    $mysqli,
                    "SELECT * FROM table_giohang WHERE id_khachhang = $customerId AND code_cart = '$codeSafe' LIMIT 1"
                );
                $order = $qOrder ? mysqli_fetch_assoc($qOrder) : null;

                if (!$order) {
                    echo '<div class="alert alert-error cart-alert">Không tìm thấy đơn hàng hoặc bạn không có quyền xem đơn này.</div>';
                } else {
                    $addr = '';
                    if (!empty($order['ap'])) {
                        $addr = trim($order['ap'] . ', ' . ($order['xa'] ?? '') . ', ' . ($order['tinh'] ?? ''), ', ');
                    } else {
                        $addr = (string)$defaultAddress;
                    }
                    $phone = !empty($order['dienthoai']) ? $order['dienthoai'] : $defaultPhone;

                    $itemsSql = "SELECT sanpham.tensanpham, sanpham.giasp, sanpham.hinhanh, sanpham.masp, table_chitietdonhang.soluongmua
                                FROM table_chitietdonhang
                                JOIN sanpham ON table_chitietdonhang.id_sanpham = sanpham.id_sanpham
                                WHERE table_chitietdonhang.code_cart = '$codeSafe'
                                ORDER BY table_chitietdonhang.id_cart_details DESC";
                    $qItems = mysqli_query($mysqli, $itemsSql);

                    $total = 0;
                    $totalQty = 0;
            ?>

                <div class="purchase-detail">
                    <div class="purchase-detail-head">
                        <div>
                            <div class="purchase-detail-title">Chi tiết đơn hàng</div>
                            <div class="purchase-detail-sub">Mã đơn: <strong><?php echo htmlspecialchars($order['code_cart']); ?></strong></div>
                        </div>
                        <div class="purchase-detail-actions">
                            <a class="btn-secondary" href="index.php?quanly=donmua">Quay lại</a>
                        </div>
                    </div>

                    <div class="purchase-meta">
                        <div class="purchase-meta-row">
                            <span class="purchase-meta-label">Tình trạng</span>
                            <span class="purchase-meta-value"><?php
                                $orderStatus = $hasOrderStatus ? ($order['order_status'] ?? 0) : 0;
                                echo renderStatusBadge($orderStatus, $order['cart_status'] ?? 1);
                            ?></span>
                        </div>
                        <div class="purchase-meta-row">
                            <span class="purchase-meta-label">Số điện thoại</span>
                            <span class="purchase-meta-value"><?php echo htmlspecialchars((string)$phone); ?></span>
                        </div>
                        <div class="purchase-meta-row">
                            <span class="purchase-meta-label">Địa chỉ</span>
                            <span class="purchase-meta-value"><?php echo htmlspecialchars((string)$addr); ?></span>
                        </div>
                        <?php if (!empty($order['ghichu'])) { ?>
                            <div class="purchase-meta-row">
                                <span class="purchase-meta-label">Ghi chú</span>
                                <span class="purchase-meta-value"><?php echo htmlspecialchars((string)$order['ghichu']); ?></span>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="purchase-section">Danh sách sản phẩm</div>
                    <div class="table-scroll" role="region" aria-label="Bảng chi tiết đơn hàng">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th class="col-id">#</th>
                                    <th>Hình ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($qItems) {
                                    $i = 0;
                                    while ($row = mysqli_fetch_assoc($qItems)) {
                                        $i++;
                                        $qty = (int)($row['soluongmua'] ?? 0);
                                        $price = (float)($row['giasp'] ?? 0);
                                        $lineTotal = $qty * $price;
                                        $total += $lineTotal;
                                        $totalQty += $qty;

                                        $fn = trim((string)($row['hinhanh'] ?? ''));
                                        $serverPath = __DIR__ . '/../../admincp/modules/quanlysp/upload/' . $fn;
                                        if ($fn !== '' && is_file($serverPath)) {
                                            $imgSrc = 'admincp/modules/quanlysp/upload/' . rawurlencode($fn);
                                        } else {
                                            $imgSrc = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
                                        }
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td>
                                        <img class="purchase-item-img" alt="<?php echo htmlspecialchars((string)$row['tensanpham']); ?>" src="<?php echo $imgSrc; ?>">
                                    </td>
                                    <td class="cell-name"><?php echo htmlspecialchars((string)$row['tensanpham']); ?></td>
                                    <td><?php echo $qty; ?></td>
                                    <td class="cart-price"><?php echo number_format((int)$price, 0, ',', '.'); ?>đ</td>
                                    <td class="cart-total"><?php echo number_format((int)$lineTotal, 0, ',', '.'); ?>đ</td>
                                </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr class="cart-summary-row">
                                    <td colspan="5">
                                        <div class="purchase-summary">
                                            <div>
                                                <div class="purchase-summary-label">Tổng số lượng</div>
                                                <div class="purchase-summary-value"><?php echo (int)$totalQty; ?></div>
                                            </div>
                                            <div>
                                                <div class="purchase-summary-label">Tổng tiền</div>
                                                <div class="purchase-summary-value"><?php echo number_format((int)$total, 0, ',', '.'); ?>đ</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <?php
                        $curStatus = $hasOrderStatus ? (int)($order['order_status'] ?? 1) : 0;
                        $canCancel = $hasOrderStatus && in_array($curStatus, [1, 2, 3], true);
                        $canRebuy = (!$hasOrderStatus) || in_array($curStatus, [4, 5], true);
                    ?>

                    <div class="purchase-actions purchase-actions--detail-bottom">
                        <?php if ($canCancel) { ?>
                            <form method="post" action="index.php?quanly=donmua&code=<?php echo urlencode((string)$order['code_cart']); ?>" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?');">
                                <input type="hidden" name="purchase_action" value="cancel">
                                <input type="hidden" name="code" value="<?php echo htmlspecialchars((string)$order['code_cart']); ?>">
                                <button type="submit" class="btn-danger">Hủy đơn hàng</button>
                            </form>
                        <?php } ?>

                        <?php if ($canRebuy) { ?>
                            <form method="post" action="index.php?quanly=donmua&code=<?php echo urlencode((string)$order['code_cart']); ?>">
                                <input type="hidden" name="purchase_action" value="rebuy">
                                <input type="hidden" name="code" value="<?php echo htmlspecialchars((string)$order['code_cart']); ?>">
                                <button type="submit" class="btn-primary">Mua lại đơn này</button>
                            </form>
                        <?php } ?>
                    </div>
                </div>

            <?php
                }
            } else {
                // Danh sách đơn mua
                $sql = "SELECT g.id_cart, g.code_cart, g.cart_status" . ($hasOrderStatus ? ", g.order_status" : "") . ", g.ap, g.xa, g.tinh, g.ghichu, g.dienthoai,
                               COALESCE(SUM(sp.giasp * ct.soluongmua), 0) AS tongtien,
                               COALESCE(SUM(ct.soluongmua), 0) AS tongsl
                        FROM table_giohang g
                        LEFT JOIN table_chitietdonhang ct ON g.code_cart = ct.code_cart
                        LEFT JOIN sanpham sp ON ct.id_sanpham = sp.id_sanpham
                        WHERE g.id_khachhang = $customerId
                    " . ($hasOrderStatus && $st !== '' ? (" AND g.order_status = " . (int)$st) : "") . "
                        GROUP BY g.id_cart
                        ORDER BY g.id_cart DESC";
                $q = mysqli_query($mysqli, $sql);

                if (!$q || mysqli_num_rows($q) === 0) {
                    echo '<div class="empty-cart"><div class="empty-cart-inner"><div class="empty-cart-title">Bạn chưa có đơn hàng nào</div><div class="empty-cart-sub">Hãy mua sắm để tạo đơn hàng đầu tiên.</div><a class="btn-primary" href="index.php">Mua sắm ngay</a></div></div>';
                } else {
            ?>

                <div class="purchase-list">
                    <?php while ($row = mysqli_fetch_assoc($q)) {
                        $addr = '';
                        if (!empty($row['ap'])) {
                            $addr = trim((string)$row['ap'] . ', ' . (string)($row['xa'] ?? '') . ', ' . (string)($row['tinh'] ?? ''), ', ');
                        } else {
                            $addr = (string)$defaultAddress;
                        }
                        $phone = !empty($row['dienthoai']) ? $row['dienthoai'] : $defaultPhone;
                    ?>
                        <div class="purchase-card">
                            <div class="purchase-card-head">
                                <div class="purchase-code">Mã đơn <strong><?php echo htmlspecialchars((string)$row['code_cart']); ?></strong></div>
                                <div class="purchase-status"><?php
                                    $orderStatus = $hasOrderStatus ? ($row['order_status'] ?? 0) : 0;
                                    echo renderStatusBadge($orderStatus, $row['cart_status'] ?? 1);
                                ?></div>
                            </div>

                            <div class="purchase-card-body">
                                <div class="purchase-kv">
                                    <div class="purchase-kv-row"><span class="purchase-kv-label">Số lượng</span><span class="purchase-kv-value"><?php echo (int)($row['tongsl'] ?? 0); ?></span></div>
                                    <div class="purchase-kv-row"><span class="purchase-kv-label">Tổng tiền</span><span class="purchase-kv-value"><?php echo number_format((int)($row['tongtien'] ?? 0), 0, ',', '.'); ?>đ</span></div>
                                </div>

                                <div class="purchase-mini">
                                    <div><span class="purchase-mini-label">SĐT:</span> <?php echo htmlspecialchars((string)$phone); ?></div>
                                    <div><span class="purchase-mini-label">Địa chỉ:</span> <?php echo htmlspecialchars((string)$addr); ?></div>
                                </div>

                                <div class="purchase-actions">
                                    <a class="btn-primary" href="index.php?quanly=donmua&code=<?php echo urlencode((string)$row['code_cart']); ?>">Xem chi tiết</a>
                                    <a class="btn-secondary" href="index.php">Mua tiếp</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            <?php
                }
            }
            ?>

        <?php } ?>
    </div>
</div>
