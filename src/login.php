<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $email = trim($data['email'] ?? '');
    $password = $data['pass'] ?? '';
    
    $errors = [];
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Некорректный email';
    }
    if (empty($password)) {
        $errors[] = 'Пароль обязателен';
    }
    
    if (empty($errors)) {
        $hashed_password = md5($password);
        
        $stmt = $pdo->prepare("SELECT id, name, email, phone FROM users WHERE email = ? AND password = ?");
        $stmt->execute([$email, $hashed_password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['logged_in'] = true;
            
            echo json_encode([
                'success' => true,
                'message' => 'Авторизация успешна!',
                'user' => $user
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'errors' => ['Неверный email или пароль']
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
    }
}
?>