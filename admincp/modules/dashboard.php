<?php
if (!isset($mysqli)) {
	echo '<div class="admin-alert admin-alert--error" role="alert">Không thể kết nối CSDL.</div>';
	return;
}

$hasOrderStatus = false;
try {
	$qHas = mysqli_query($mysqli, "SHOW COLUMNS FROM table_giohang LIKE 'order_status'");
	if ($qHas && mysqli_num_rows($qHas) > 0) {
		$hasOrderStatus = true;
	}
} catch (mysqli_sql_exception $e) {
	$hasOrderStatus = false;
}

$hasCancelRequested = false;
try {
	$qHas = mysqli_query($mysqli, "SHOW COLUMNS FROM table_giohang LIKE 'cancel_requested'");
	if ($qHas && mysqli_num_rows($qHas) > 0) {
		$hasCancelRequested = true;
	}
} catch (mysqli_sql_exception $e) {
	$hasCancelRequested = false;
}

$fetchScalar = function (string $sql, $default = 0) use ($mysqli) {
	try {
		$r = mysqli_query($mysqli, $sql);
		if (!$r) return $default;
		$row = mysqli_fetch_row($r);
		if (!$row) return $default;
		return $row[0] ?? $default;
	} catch (mysqli_sql_exception $e) {
		return $default;
	}
};

$totalCategories = (int)$fetchScalar('SELECT COUNT(*) FROM danhmuc', 0);
$totalProducts = (int)$fetchScalar('SELECT COUNT(*) FROM sanpham', 0);
$lowStockProducts = (int)$fetchScalar('SELECT COUNT(*) FROM sanpham WHERE soluong <= 5', 0);

$totalOrders = (int)$fetchScalar('SELECT COUNT(*) FROM table_giohang', 0);
$newOrders = (int)$fetchScalar('SELECT COUNT(*) FROM table_giohang WHERE cart_status=1', 0);

$completedOrders = 0;
$revenueCompleted = 0;
if ($hasOrderStatus) {
	$completedOrders = (int)$fetchScalar('SELECT COUNT(*) FROM table_giohang WHERE order_status=4', 0);
	$revenueCompleted = (float)$fetchScalar(
		'SELECT COALESCE(SUM(sp.giasp * ct.soluongmua), 0) '
		. 'FROM table_giohang g '
		. 'JOIN table_chitietdonhang ct ON g.code_cart = ct.code_cart '
		. 'JOIN sanpham sp ON ct.id_sanpham = sp.id_sanpham '
		. 'WHERE g.order_status=4',
		0
	);
}

$statusLabels = [
	1 => 'Chờ thanh toán',
	2 => 'Vận chuyển',
	3 => 'Chờ giao hàng',
	4 => 'Hoàn thành',
	5 => 'Đã hủy',
	6 => 'Trả hàng/Hoàn tiền',
];

$statusCounts = [];
if ($hasOrderStatus) {
	try {
		$q = mysqli_query($mysqli, 'SELECT order_status, COUNT(*) AS cnt FROM table_giohang GROUP BY order_status');
		if ($q) {
			while ($row = mysqli_fetch_assoc($q)) {
				$k = (int)($row['order_status'] ?? 0);
				$statusCounts[$k] = (int)($row['cnt'] ?? 0);
			}
		}
	} catch (mysqli_sql_exception $e) {
		$statusCounts = [];
	}
}

$renderOrderBadge = function ($orderStatus, $cartStatus, $cancelRequested = 0) use ($hasOrderStatus, $hasCancelRequested) {
	$cartStatus = (int)$cartStatus;
	if (!$hasOrderStatus) {
		return ($cartStatus === 1)
			? '<span class="admin-badge admin-badge--new">Đơn hàng mới</span>'
			: '<span class="admin-badge admin-badge--seen">Đã xem</span>';
	}

	if ($hasCancelRequested && (int)$cancelRequested === 1 && (int)$orderStatus !== 5) {
		return '<span class="admin-status admin-status--pending">Yêu cầu hủy</span>';
	}

	$s = (int)$orderStatus;
	$map = [
		1 => ['cls' => 'admin-status admin-status--pending', 'text' => 'Chờ thanh toán'],
		2 => ['cls' => 'admin-status admin-status--shipping', 'text' => 'Vận chuyển'],
		3 => ['cls' => 'admin-status admin-status--delivering', 'text' => 'Chờ giao hàng'],
		4 => ['cls' => 'admin-status admin-status--done', 'text' => 'Hoàn thành'],
		5 => ['cls' => 'admin-status admin-status--cancel', 'text' => 'Đã hủy'],
		6 => ['cls' => 'admin-status admin-status--refund', 'text' => 'Trả hàng/Hoàn tiền'],
	];
	if (!isset($map[$s])) {
		return '<span class="admin-badge admin-badge--seen">Không rõ</span>';
	}
	return '<span class="' . $map[$s]['cls'] . '">' . htmlspecialchars($map[$s]['text']) . '</span>';
};

$recentOrders = [];
try {
	$sql = "SELECT g.id_cart, g.code_cart, g.cart_status" . ($hasOrderStatus ? ", g.order_status" : "") . ",
			   " . ($hasCancelRequested ? " g.cancel_requested," : "") . "
				   d.tenkhachhang,
				   COALESCE(SUM(sp.giasp * ct.soluongmua), 0) AS tongtien,
				   COALESCE(SUM(ct.soluongmua), 0) AS tongsl
			FROM table_giohang g
			JOIN table_dangky d ON g.id_khachhang = d.id_dangky
			LEFT JOIN table_chitietdonhang ct ON g.code_cart = ct.code_cart
			LEFT JOIN sanpham sp ON ct.id_sanpham = sp.id_sanpham
			GROUP BY g.id_cart
			ORDER BY g.id_cart DESC
			LIMIT 8";
	$qRecent = mysqli_query($mysqli, $sql);
	if ($qRecent) {
		while ($row = mysqli_fetch_assoc($qRecent)) {
			$recentOrders[] = $row;
		}
	}
} catch (mysqli_sql_exception $e) {
	$recentOrders = [];
}

$topProducts = [];
try {
	$qTop = mysqli_query(
		$mysqli,
		'SELECT sp.id_sanpham, sp.tensanpham, COALESCE(SUM(ct.soluongmua), 0) AS sold '
		. 'FROM table_chitietdonhang ct '
		. 'JOIN sanpham sp ON ct.id_sanpham = sp.id_sanpham '
		. 'GROUP BY sp.id_sanpham '
		. 'ORDER BY sold DESC '
		. 'LIMIT 5'
	);
	if ($qTop) {
		while ($row = mysqli_fetch_assoc($qTop)) {
			$topProducts[] = $row;
		}
	}
} catch (mysqli_sql_exception $e) {
	$topProducts = [];
}
?>

<div class="admin-page-head">
	<div>
		<div class="admin-page-title">Tổng quan</div>
		<div class="admin-page-sub">Các thống kê cơ bản cho hệ thống bán hàng.</div>
	</div>
</div>

<div class="admin-stats-grid" role="list">
	<div class="admin-stat" role="listitem">
		<div class="admin-stat-label">Danh mục</div>
		<div class="admin-stat-value"><?php echo number_format($totalCategories, 0, ',', '.'); ?></div>
		<div class="admin-stat-sub">Tổng số danh mục sản phẩm</div>
	</div>

	<div class="admin-stat" role="listitem">
		<div class="admin-stat-label">Sản phẩm</div>
		<div class="admin-stat-value"><?php echo number_format($totalProducts, 0, ',', '.'); ?></div>
		<div class="admin-stat-sub">Tổng số sản phẩm</div>
	</div>

	<div class="admin-stat" role="listitem">
		<div class="admin-stat-label">Tồn kho thấp</div>
		<div class="admin-stat-value"><?php echo number_format($lowStockProducts, 0, ',', '.'); ?></div>
		<div class="admin-stat-sub">Sản phẩm có số lượng ≤ 5</div>
	</div>

	<div class="admin-stat" role="listitem">
		<div class="admin-stat-label">Đơn hàng</div>
		<div class="admin-stat-value"><?php echo number_format($totalOrders, 0, ',', '.'); ?></div>
		<div class="admin-stat-sub">Tổng số đơn đã tạo</div>
	</div>

	<div class="admin-stat admin-stat--highlight" role="listitem">
		<div class="admin-stat-label">Đơn mới</div>
		<div class="admin-stat-value"><?php echo number_format($newOrders, 0, ',', '.'); ?></div>
		<div class="admin-stat-sub">Chưa xem/xác nhận</div>
	</div>

	<div class="admin-stat" role="listitem">
		<div class="admin-stat-label">Doanh thu</div>
		<div class="admin-stat-value"><?php echo $hasOrderStatus ? number_format((int)$revenueCompleted, 0, ',', '.') . 'đ' : '—'; ?></div>
		<div class="admin-stat-sub"><?php echo $hasOrderStatus ? ('Từ đơn hoàn thành (' . (int)$completedOrders . ')') : 'Chưa bật trạng thái đơn chi tiết'; ?></div>
	</div>
</div>

<div class="admin-dashboard-grid">
	<section class="admin-panel admin-panel--page">
		<div class="admin-page-head" style="margin-top:0;">
			<div>
				<div class="admin-page-title">Đơn hàng gần đây</div>
				<div class="admin-page-sub">Tối đa 8 đơn mới nhất.</div>
			</div>
			<div class="admin-actions">
				<a class="btn" href="index.php?action=quanlydonhang&query=lietke">Xem tất cả</a>
			</div>
		</div>

		<div class="table-wrap">
			<table class="styled-table" border="1px">
				<tr>
					<th>Mã đơn</th>
					<th>Khách hàng</th>
					<th>Số lượng</th>
					<th>Tổng tiền</th>
					<th>Trạng thái</th>
					<th></th>
				</tr>
				<?php if (empty($recentOrders)) { ?>
					<tr>
						<td colspan="6">Chưa có dữ liệu đơn hàng.</td>
					</tr>
				<?php } else { ?>
					<?php foreach ($recentOrders as $row) { ?>
						<tr>
							<td><strong><?php echo htmlspecialchars((string)$row['code_cart']); ?></strong></td>
							<td class="td-clamp"><?php echo htmlspecialchars((string)($row['tenkhachhang'] ?? '')); ?></td>
							<td><?php echo (int)($row['tongsl'] ?? 0); ?></td>
							<td><?php echo number_format((int)($row['tongtien'] ?? 0), 0, ',', '.'); ?>đ</td>
							<td><?php
								$os = $hasOrderStatus ? (int)($row['order_status'] ?? 1) : 0;
								$cr = $hasCancelRequested ? (int)($row['cancel_requested'] ?? 0) : 0;
								echo $renderOrderBadge($os, $row['cart_status'] ?? 0, $cr);
							?></td>
							<td>
								<div class="admin-actions">
									<a class="btn edit-btn" href="index.php?action=donhang&query=xemdonhang&code=<?php echo urlencode((string)$row['code_cart']); ?>">Xem</a>
								</div>
							</td>
						</tr>
					<?php } ?>
				<?php } ?>
			</table>
		</div>
	</section>

	<section class="admin-panel admin-panel--page">
		<div class="admin-page-head" style="margin-top:0;">
			<div>
				<div class="admin-page-title">Trạng thái đơn</div>
				<div class="admin-page-sub"><?php echo $hasOrderStatus ? 'Theo trạng thái chi tiết.' : 'Hệ thống đang dùng trạng thái đơn đơn giản (mới/đã xem).'; ?></div>
			</div>
		</div>

		<?php if (!$hasOrderStatus) { ?>
			<div class="admin-kv">
				<div class="admin-kv-row"><span class="admin-kv-label">Đơn mới</span><span class="admin-kv-value"><?php echo (int)$newOrders; ?></span></div>
				<div class="admin-kv-row"><span class="admin-kv-label">Đã xem</span><span class="admin-kv-value"><?php echo max(0, (int)$totalOrders - (int)$newOrders); ?></span></div>
			</div>
		<?php } else { ?>
			<div class="admin-kv">
				<?php foreach ($statusLabels as $k => $label) {
					$cnt = (int)($statusCounts[$k] ?? 0);
				?>
					<div class="admin-kv-row"><span class="admin-kv-label"><?php echo htmlspecialchars($label); ?></span><span class="admin-kv-value"><?php echo $cnt; ?></span></div>
				<?php } ?>
			</div>
		<?php } ?>
	</section>
</div>

<section class="admin-panel admin-panel--page" style="margin-top:14px;">
	<div class="admin-page-head" style="margin-top:0;">
		<div>
			<div class="admin-page-title">Top sản phẩm bán chạy</div>
			<div class="admin-page-sub">Theo tổng số lượng trong chi tiết đơn.</div>
		</div>
	</div>

	<div class="table-wrap">
		<table class="styled-table" border="1px">
			<tr>
				<th>Sản phẩm</th>
				<th>Đã bán</th>
			</tr>
			<?php if (empty($topProducts)) { ?>
				<tr>
					<td colspan="2">Chưa có dữ liệu.</td>
				</tr>
			<?php } else { ?>
				<?php foreach ($topProducts as $row) { ?>
					<tr>
						<td class="td-clamp"><?php echo htmlspecialchars((string)$row['tensanpham']); ?></td>
						<td><strong><?php echo (int)($row['sold'] ?? 0); ?></strong></td>
					</tr>
				<?php } ?>
			<?php } ?>
		</table>
	</div>
</section>