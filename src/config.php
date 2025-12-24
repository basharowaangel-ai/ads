<?php
ini_set('session.cookie_lifetime', 86400 * 7);
ini_set('session.gc_maxlifetime', 86400 * 7);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']) &&
        isset($_SESSION['logged_in']) &&
        $_SESSION['logged_in'] === true;
}

function logout()
{
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();
}

function generateCsrfToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrfToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function requireAuth()
{
    if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])) {
        $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
        header('Location: /index.php?message=Для доступа необходимо авторизоваться');
        exit;
    }
}
