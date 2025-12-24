<?php
require_once __DIR__ . '/config.php';

logout();

echo json_encode([
    'success' => true,
    'message' => 'Вы вышли из системы'
]);
