<?php
if (!isset($_SESSION['dangnhap']) || $_SESSION['dangnhap'] === '') {
    echo '<div class="admin-alert admin-alert--error" role="alert">Bạn chưa đăng nhập.</div>';
    return;
}

$currentUsername = (string)$_SESSION['dangnhap'];

// Load current admin row
$stmt = $mysqli->prepare('SELECT username, password FROM admin WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $currentUsername);
$stmt->execute();
$result = $stmt->get_result();
$adminRow = $result ? $result->fetch_assoc() : null;

$notice = '';
$noticeType = '';

if (!$adminRow) {
    $notice = 'Không tìm thấy tài khoản admin trong hệ thống.';
    $noticeType = 'error';
} else if (isset($_POST['capnhat_taikhoan'])) {
    $newUsername = trim($_POST['username_new'] ?? '');
    $currentPassword = (string)($_POST['password_current'] ?? '');
    $newPassword = (string)($_POST['password_new'] ?? '');
    $confirmPassword = (string)($_POST['password_confirm'] ?? '');

    if ($newUsername === '') {
        $notice = 'Vui lòng nhập tên đăng nhập.';
        $noticeType = 'error';
    } else if ($currentPassword === '') {
        $notice = 'Vui lòng nhập mật khẩu hiện tại để xác nhận thay đổi.';
        $noticeType = 'error';
    } else if (md5($currentPassword) !== (string)$adminRow['password']) {
        $notice = 'Mật khẩu hiện tại không đúng.';
        $noticeType = 'error';
    } else {
        $hasUsernameChange = ($newUsername !== $currentUsername);
        $hasPasswordChange = ($newPassword !== '' || $confirmPassword !== '');

        if ($hasPasswordChange) {
            if ($newPassword === '' || $confirmPassword === '') {
                $notice = 'Vui lòng nhập đầy đủ mật khẩu mới và xác nhận mật khẩu.';
                $noticeType = 'error';
            } else if ($newPassword !== $confirmPassword) {
                $notice = 'Mật khẩu mới và xác nhận mật khẩu không khớp.';
                $noticeType = 'error';
            }
        }

        if ($noticeType !== 'error') {
            if (!$hasUsernameChange && !$hasPasswordChange) {
                $notice = 'Không có thay đổi nào để cập nhật.';
                $noticeType = 'info';
            } else {
                if ($hasUsernameChange) {
                    $stmtCheck = $mysqli->prepare('SELECT 1 FROM admin WHERE username = ? LIMIT 1');
                    $stmtCheck->bind_param('s', $newUsername);
                    $stmtCheck->execute();
                    $exists = $stmtCheck->get_result();
                    if ($exists && $exists->num_rows > 0) {
                        $notice = 'Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.';
                        $noticeType = 'error';
                    }
                }

                if ($noticeType !== 'error') {
                    $targetUsername = $currentUsername;

                    if ($hasUsernameChange) {
                        $stmtU = $mysqli->prepare('UPDATE admin SET username = ? WHERE username = ? LIMIT 1');
                        $stmtU->bind_param('ss', $newUsername, $currentUsername);
                        $stmtU->execute();
                        if ($stmtU->affected_rows >= 0) {
                            $targetUsername = $newUsername;
                            $_SESSION['dangnhap'] = $newUsername;
                            $currentUsername = $newUsername;
                        }
                    }

                    if ($hasPasswordChange) {
                        $newHash = md5($newPassword);
                        $stmtP = $mysqli->prepare('UPDATE admin SET password = ? WHERE username = ? LIMIT 1');
                        $stmtP->bind_param('ss', $newHash, $targetUsername);
                        $stmtP->execute();
                    }

                    // Reload row
                    $stmtR = $mysqli->prepare('SELECT username, password FROM admin WHERE username = ? LIMIT 1');
                    $stmtR->bind_param('s', $currentUsername);
                    $stmtR->execute();
                    $resR = $stmtR->get_result();
                    $adminRow = $resR ? $resR->fetch_assoc() : $adminRow;

                    $notice = 'Cập nhật thông tin tài khoản thành công.';
                    $noticeType = 'success';
                }
            }
        }
    }
}

$displayUsername = $adminRow ? (string)$adminRow['username'] : $currentUsername;
?>

<div class="admin-page-head">
    <div>
        <div class="admin-page-title">Thông tin tài khoản</div>
        <div class="admin-page-sub">Cập nhật tên đăng nhập và mật khẩu quản trị.</div>
    </div>
</div>

<?php if ($notice !== '') { ?>
    <div class="admin-alert <?php echo ($noticeType === 'success') ? 'admin-alert--success' : (($noticeType === 'info') ? 'admin-alert--info' : 'admin-alert--error'); ?>" role="alert" aria-live="polite">
        <div class="admin-alert-content">
            <div class="admin-alert-title"><?php echo ($noticeType === 'success') ? 'Thành công' : (($noticeType === 'info') ? 'Thông báo' : 'Có lỗi'); ?></div>
            <div class="admin-alert-desc"><?php echo htmlspecialchars($notice); ?></div>
        </div>
    </div>
<?php } ?>

<div class="form-container form-container--panel">
    <form method="POST" class="styled-form styled-form--wide" autocomplete="off">
        <div class="admin-form-grid">
            <div class="span-2">
                <label class="form-label" for="admin_username_new">Tên đăng nhập</label>
                <div class="admin-input">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path fill="currentColor" d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2c-4.1 0-7.5 2.2-7.5 5a1 1 0 1 0 2 0c0-1.5 2.5-3 5.5-3s5.5 1.5 5.5 3a1 1 0 1 0 2 0c0-2.8-3.4-5-7.5-5Z"/>
                    </svg>
                    <input id="admin_username_new" type="text" name="username_new" value="<?php echo htmlspecialchars($displayUsername); ?>" required />
                </div>
                <div class="admin-help">Tên hiện tại: <strong><?php echo htmlspecialchars($currentUsername); ?></strong></div>
            </div>

            <div class="span-2">
                <label class="form-label" for="admin_password_current">Mật khẩu hiện tại</label>
                <div class="admin-input has-toggle">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path fill="currentColor" d="M12 1a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5Zm-3 8V6a3 3 0 0 1 6 0v3H9Z"/>
                    </svg>
                    <input id="admin_password_current" type="password" name="password_current" placeholder="Nhập mật khẩu hiện tại" required />
                    <button type="button" class="admin-toggle" data-target="admin_password_current" aria-label="Ẩn/hiện mật khẩu" aria-pressed="false">
                        <svg class="icon-eye" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 5c5.5 0 9.7 5.2 9.9 5.4a1 1 0 0 1 0 1.2C21.7 11.8 17.5 17 12 17S2.3 11.8 2.1 11.6a1 1 0 0 1 0-1.2C2.3 10.2 6.5 5 12 5Zm0 10a4 4 0 1 0-4-4a4 4 0 0 0 4 4Z"/></svg>
                        <svg class="icon-eye-off" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M3.3 2.3a1 1 0 0 1 1.4 0l16 16a1 1 0 1 1-1.4 1.4l-2.1-2.1A10.9 10.9 0 0 1 12 19C6.5 19 2.3 13.8 2.1 13.6a1 1 0 0 1 0-1.2A18.6 18.6 0 0 1 6.2 8.3L3.3 5.4a1 1 0 0 1 0-1.4ZM12 7c-.5 0-1 .05-1.5.14l2.1 2.1A4 4 0 0 1 15.7 12l2.2 2.2a18.2 18.2 0 0 0 2-2.6C18.7 10.2 15.5 7 12 7Zm-6.1 4.1a18.2 18.2 0 0 0-1.8 2.5C5.3 15 8.5 18 12 18c1.4 0 2.7-.4 3.8-1l-1.6-1.6A4 4 0 0 1 8.6 9.8L5.9 7.1Z"/></svg>
                    </button>
                </div>
            </div>

            <div>
                <label class="form-label" for="admin_password_new">Mật khẩu mới</label>
                <div class="admin-input has-toggle">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path fill="currentColor" d="M12 1a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5Zm-3 8V6a3 3 0 0 1 6 0v3H9Z"/>
                    </svg>
                    <input id="admin_password_new" type="password" name="password_new" placeholder="Nhập mật khẩu mới" />
                    <button type="button" class="admin-toggle" data-target="admin_password_new" aria-label="Ẩn/hiện mật khẩu" aria-pressed="false">
                        <svg class="icon-eye" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 5c5.5 0 9.7 5.2 9.9 5.4a1 1 0 0 1 0 1.2C21.7 11.8 17.5 17 12 17S2.3 11.8 2.1 11.6a1 1 0 0 1 0-1.2C2.3 10.2 6.5 5 12 5Zm0 10a4 4 0 1 0-4-4a4 4 0 0 0 4 4Z"/></svg>
                        <svg class="icon-eye-off" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M3.3 2.3a1 1 0 0 1 1.4 0l16 16a1 1 0 1 1-1.4 1.4l-2.1-2.1A10.9 10.9 0 0 1 12 19C6.5 19 2.3 13.8 2.1 13.6a1 1 0 0 1 0-1.2A18.6 18.6 0 0 1 6.2 8.3L3.3 5.4a1 1 0 0 1 0-1.4ZM12 7c-.5 0-1 .05-1.5.14l2.1 2.1A4 4 0 0 1 15.7 12l2.2 2.2a18.2 18.2 0 0 0 2-2.6C18.7 10.2 15.5 7 12 7Zm-6.1 4.1a18.2 18.2 0 0 0-1.8 2.5C5.3 15 8.5 18 12 18c1.4 0 2.7-.4 3.8-1l-1.6-1.6A4 4 0 0 1 8.6 9.8L5.9 7.1Z"/></svg>
                    </button>
                </div>
                <div class="admin-help">Để trống nếu không đổi mật khẩu.</div>
            </div>

            <div>
                <label class="form-label" for="admin_password_confirm">Xác nhận mật khẩu mới</label>
                <div class="admin-input has-toggle">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path fill="currentColor" d="M12 1a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5Zm-3 8V6a3 3 0 0 1 6 0v3H9Z"/>
                    </svg>
                    <input id="admin_password_confirm" type="password" name="password_confirm" placeholder="Nhập lại mật khẩu mới" />
                    <button type="button" class="admin-toggle" data-target="admin_password_confirm" aria-label="Ẩn/hiện mật khẩu" aria-pressed="false">
                        <svg class="icon-eye" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 5c5.5 0 9.7 5.2 9.9 5.4a1 1 0 0 1 0 1.2C21.7 11.8 17.5 17 12 17S2.3 11.8 2.1 11.6a1 1 0 0 1 0-1.2C2.3 10.2 6.5 5 12 5Zm0 10a4 4 0 1 0-4-4a4 4 0 0 0 4 4Z"/></svg>
                        <svg class="icon-eye-off" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M3.3 2.3a1 1 0 0 1 1.4 0l16 16a1 1 0 1 1-1.4 1.4l-2.1-2.1A10.9 10.9 0 0 1 12 19C6.5 19 2.3 13.8 2.1 13.6a1 1 0 0 1 0-1.2A18.6 18.6 0 0 1 6.2 8.3L3.3 5.4a1 1 0 0 1 0-1.4ZM12 7c-.5 0-1 .05-1.5.14l2.1 2.1A4 4 0 0 1 15.7 12l2.2 2.2a18.2 18.2 0 0 0 2-2.6C18.7 10.2 15.5 7 12 7Zm-6.1 4.1a18.2 18.2 0 0 0-1.8 2.5C5.3 15 8.5 18 12 18c1.4 0 2.7-.4 3.8-1l-1.6-1.6A4 4 0 0 1 8.6 9.8L5.9 7.1Z"/></svg>
                    </button>
                </div>
            </div>

            <div class="span-2" style="margin-top: 6px;">
                <button class="submit-btn" type="submit" name="capnhat_taikhoan" value="1">Cập nhật</button>
            </div>
        </div>
    </form>
</div>

<script>
(function () {
    var toggles = document.querySelectorAll('.admin-toggle[data-target]');
    if (!toggles || !toggles.length) return;

    toggles.forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            var targetId = toggle.getAttribute('data-target');
            if (!targetId) return;
            var input = document.getElementById(targetId);
            if (!input) return;
            var isPassword = input.getAttribute('type') === 'password';
            input.setAttribute('type', isPassword ? 'text' : 'password');
            toggle.classList.toggle('is-on', isPassword);
            toggle.setAttribute('aria-pressed', isPassword ? 'true' : 'false');
        });
    });
})();
</script>
