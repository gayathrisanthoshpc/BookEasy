<?php
$settings = [
    'db_host' => getenv('BOOKEASY_DB_HOST') ?: 'localhost',
    'db_name' => getenv('BOOKEASY_DB_NAME') ?: 'booking_db',
    'db_user' => getenv('BOOKEASY_DB_USER') ?: 'root',
    'db_pass' => getenv('BOOKEASY_DB_PASS') === false ? '' : getenv('BOOKEASY_DB_PASS'),
    'admin_pass' => getenv('BOOKEASY_ADMIN_PASS') ?: 'change-this-admin-password',
];
$localConfig = __DIR__ . '/config.local.php';
if (is_file($localConfig)) {
    $localSettings = require $localConfig;
    if (is_array($localSettings)) $settings = array_merge($settings, $localSettings);
}
define('DB_HOST', $settings['db_host']);
define('DB_NAME', $settings['db_name']);
define('DB_USER', $settings['db_user']);
define('DB_PASS', $settings['db_pass']);
define('ADMIN_PASS', $settings['admin_pass']);
