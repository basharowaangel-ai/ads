<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

// Проверяем авторизацию
if (!isLoggedIn()) {
    echo json_encode([
        'success' => false,
        'message' => 'Для добавления объявления необходимо авторизоваться'
    ]);
    exit;
}

// Проверяем метод запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Неверный метод запроса'
    ]);
    exit;
}

$title = trim($_POST['title'] ?? '');
$price = trim($_POST['price'] ?? '');
$description = trim($_POST['description'] ?? '');
$user_id = $_SESSION['user_id'];

$errors = [];

if (empty($title)) {
    $errors[] = 'Название обязательно';
} elseif (strlen($title) > 225) {
    $errors[] = 'Название слишком длинное (макс. 225 символов)';
}

if (empty($price)) {
    $errors[] = 'Цена обязательна';
} elseif (!is_numeric($price) || $price <= 0) {
    $errors[] = 'Цена должна быть положительным числом';
} else {
    $price = number_format((float)$price, 2, '.', '');
}

if (empty($description)) {
    $errors[] = 'Описание обязательно';
} elseif (strlen($description) > 1000) {
    $errors[] = 'Описание слишком длинное (макс. 1000 символов)';
}

$image_data = null;
$image_type = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['image'];

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $file_type = mime_content_type($file['tmp_name']);

    if (!in_array($file_type, $allowed_types)) {
        $errors[] = 'Разрешены только изображения (JPEG, PNG, GIF, WebP)';
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        $errors[] = 'Размер изображения не должен превышать 5MB';
    }

    if (empty($errors)) {
        $image_data = file_get_contents($file['tmp_name']);
        $image_type = $file_type;
    }
} else {
    $errors[] = 'Изображение обязательно';
}

// Если есть ошибки - возвращаем
if (!empty($errors)) {
    echo json_encode([
        'success' => false,
        'errors' => $errors
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO Ads (title, price, description, image, user_id) 
        VALUES (:title, :price, :description, :image, :user_id)
    ");

    $stmt->execute([
        ':title' => $title,
        ':price' => $price,
        ':description' => $description,
        ':image' => $image_data,
        ':user_id' => $user_id
    ]);

    $ad_id = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Объявление успешно добавлено!',
        'ad_id' => $ad_id,
        'redirect' => '/ad/' . $ad_id 
    ]);
} catch (Exception $e) {
    error_log("Add ad error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Ошибка при добавлении объявления'
    ]);
}
