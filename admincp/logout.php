<?php
session_start();

// Clear all session data
$_SESSION = [];

// Clear session cookie
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'] ?? '/',
        $params['domain'] ?? '',
        (bool)($params['secure'] ?? false),
        (bool)($params['httponly'] ?? true)
    );
}

@session_destroy();

// If called from a normal link, redirect back to login
if (isset($_GET['redirect']) && $_GET['redirect'] == '1') {
    header('Location: login.php');
    exit();
}

// For beacon/fetch keepalive
http_response_code(204);
exit();
