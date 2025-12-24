<?php
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

if (isLoggedIn()) {
    echo json_encode([
        'logged_in' => true,
        'user' => [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email']
        ]
    ]);
} else {
    echo json_encode([
        'logged_in' => false
    ]);
}
