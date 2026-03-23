<div class="header">
    <div class="container header-inner">
        <a class="brand" href="index.php" style="text-decoration:none;">
            <div class="brand-mark">BH</div>
            <div>
                <div class="brand-title">Website Bán Hàng</div>
                <div class="brand-subtitle">Mua sắm nhanh • Giá tốt • Giao diện hiện đại</div>
            </div>
        </a>

        <div class="header-actions">
            <?php if (isset($_SESSION['dangky'])) { ?>
                <?php
                $userInfo = null;
                if (isset($_SESSION['id_khachhang'])) {
                    $id_khachhang = (int)$_SESSION['id_khachhang'];
                    $qUser = mysqli_query($mysqli, "SELECT tenkhachhang, email, diachi, dienthoai, ap, xa, tinh FROM table_dangky WHERE id_dangky=$id_khachhang LIMIT 1");
                    if ($qUser) {
                        $userInfo = mysqli_fetch_assoc($qUser);
                    }
                }
                $displayName = isset($userInfo['tenkhachhang']) && $userInfo['tenkhachhang'] !== ''
                    ? $userInfo['tenkhachhang']
                    : (isset($_SESSION['dangky']) ? $_SESSION['dangky'] : 'Tài khoản');
                $displayEmail = isset($userInfo['email']) ? $userInfo['email'] : '';
                $displayPhone = isset($userInfo['dienthoai']) ? $userInfo['dienthoai'] : '';

                $displayAddress = '';
                if (!empty($userInfo)) {
                    $ap = $userInfo['ap'] ?? '';
                    $xa = $userInfo['xa'] ?? '';
                    $tinh = $userInfo['tinh'] ?? '';
                    $addrParts = array_filter([$ap, $xa, $tinh], function ($v) { return is_string($v) && trim($v) !== ''; });
                    $displayAddress = trim(implode(', ', $addrParts));
                    if ($displayAddress === '') {
                        $displayAddress = $userInfo['diachi'] ?? '';
                    }
                }
                ?>

                <div class="user-menu-wrap">
                    <button type="button" class="chip chip-button js-user-menu-toggle" aria-haspopup="menu" aria-expanded="false" aria-label="Tài khoản">
                        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path fill="currentColor" d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2c-4.1 0-7.5 2.2-7.5 5a1 1 0 1 0 2 0c0-1.5 2.5-3 5.5-3s5.5 1.5 5.5 3a1 1 0 1 0 2 0c0-2.8-3.4-5-7.5-5Z"/>
                        </svg>
                        <span class="chip-text">Tài khoản</span>
                    </button>

                    <div class="user-menu" role="menu" aria-label="Thông tin người dùng">
                        <div class="user-menu-head">
                            <div class="user-avatar" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2c-4.1 0-7.5 2.2-7.5 5a1 1 0 1 0 2 0c0-1.5 2.5-3 5.5-3s5.5 1.5 5.5 3a1 1 0 1 0 2 0c0-2.8-3.4-5-7.5-5Z"/></svg>
                            </div>
                            <div>
                                <div class="user-name"><?php echo htmlspecialchars($displayName); ?></div>
                                <?php if ($displayEmail !== '') { ?>
                                    <div class="user-meta"><?php echo htmlspecialchars($displayEmail); ?></div>
                                <?php } ?>
                                <?php if ($displayPhone !== '') { ?>
                                    <div class="user-meta"><?php echo htmlspecialchars($displayPhone); ?></div>
                                <?php } ?>
                                <?php if ($displayAddress !== '') { ?>
                                    <div class="user-meta user-address"><?php echo htmlspecialchars($displayAddress); ?></div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="user-menu-links" role="none">
                            <a class="user-menu-link" role="menuitem" href="index.php?quanly=thaydoidiachi">Chỉnh sửa địa chỉ</a>
                            <a class="user-menu-link" role="menuitem" href="index.php?quanly=thaydoimatkhau">Đổi mật khẩu</a>
                            <div class="user-menu-sep" role="separator"></div>
                            <a class="user-menu-link danger" role="menuitem" href="index.php?dangxuat=1">Đăng xuất</a>
                        </div>
                    </div>
                </div>

                <a class="chip" href="index.php?quanly=giohang">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path fill="currentColor" d="M7 18a2 2 0 1 0 0 4a2 2 0 0 0 0-4Zm10 0a2 2 0 1 0 0 4a2 2 0 0 0 0-4ZM6.2 6l.3 2h13.8a1 1 0 0 1 .98 1.2l-1.2 6A2 2 0 0 1 18.14 17H7.2a2 2 0 0 1-1.97-1.65L3.28 3H2a1 1 0 1 1 0-2h2.1a1 1 0 0 1 .98.8L5.6 4H21a1 1 0 1 1 0 2H6.2Z"/>
                    </svg>
                    Giỏ hàng
                </a>
            <?php } else { ?>
                <a class="chip" href="index.php?quanly=dangnhap">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path fill="currentColor" d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2c-4.1 0-7.5 2.2-7.5 5a1 1 0 1 0 2 0c0-1.5 2.5-3 5.5-3s5.5 1.5 5.5 3a1 1 0 1 0 2 0c0-2.8-3.4-5-7.5-5Z"/>
                    </svg>
                    Đăng nhập
                </a>
                <a class="chip" href="index.php?quanly=dangky">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path fill="currentColor" d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm-7.5 7a1 1 0 1 0 2 0c0-1.5 2.5-3 5.5-3c.34 0 .68.02 1.01.06a1 1 0 1 0 .23-1.99C12.87 14.02 12.44 14 12 14c-4.1 0-7.5 2.2-7.5 5Zm14.5-5a1 1 0 0 1 1 1v2h2a1 1 0 1 1 0 2h-2v2a1 1 0 1 1-2 0v-2h-2a1 1 0 1 1 0-2h2v-2a1 1 0 0 1 1-1Z"/>
                    </svg>
                    Đăng ký
                </a>
                <a class="chip" href="index.php?quanly=giohang">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path fill="currentColor" d="M7 18a2 2 0 1 0 0 4a2 2 0 0 0 0-4Zm10 0a2 2 0 1 0 0 4a2 2 0 0 0 0-4ZM6.2 6l.3 2h13.8a1 1 0 0 1 .98 1.2l-1.2 6A2 2 0 0 1 18.14 17H7.2a2 2 0 0 1-1.97-1.65L3.28 3H2a1 1 0 1 1 0-2h2.1a1 1 0 0 1 .98.8L5.6 4H21a1 1 0 1 1 0 2H6.2Z"/>
                    </svg>
                    Giỏ hàng
                </a>
            <?php } ?>
        </div>
    </div>
</div>