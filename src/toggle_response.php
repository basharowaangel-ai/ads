<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');
error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Метод не поддерживается']);
    exit;
}

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Для отклика необходимо авторизоваться']);
    exit;
}

$ad_id = (int)($_POST['ad_id'] ?? 0);
if ($ad_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Неверный ID объявления']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $check_stmt = $pdo->prepare("SELECT id FROM Responses WHERE ad_id = ? AND user_id = ?");
    $check_stmt->execute([$ad_id, $user_id]);
    $existing_response = $check_stmt->fetch();

    if ($existing_response) {
        $delete_stmt = $pdo->prepare("DELETE FROM Responses WHERE id = ?");
        $delete_stmt->execute([$existing_response['id']]);

        echo json_encode([
            'success' => true,
            'action' => 'removed',
            'message' => 'Вы отменили отклик'
        ]);
    } else {
        $insert_stmt = $pdo->prepare("INSERT INTO Responses (ad_id, user_id) VALUES (?, ?)");
        $insert_stmt->execute([$ad_id, $user_id]);

        echo json_encode([
            'success' => true,
            'action' => 'added',
            'message' => 'Вы успешно откликнулись!'
        ]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка сервера']);
}
