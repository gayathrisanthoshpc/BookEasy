<?php
if (session_status() === PHP_SESSION_NONE) session_start();
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function csrf_token() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_check() {
    return isset($_POST['csrf']) && hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf']);
}
function old($k) { return e($_POST[$k] ?? ''); }
function err($errors, $k) { return isset($errors[$k]) ? '<span class="error">' . e($errors[$k]) . '</span>' : ''; }
