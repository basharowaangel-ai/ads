<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    $password = $data['pass'] ?? '';
    $password_repeat = $data['pass-repeat'] ?? '';
    $phone = trim($data['tel'] ?? '');

    $errors = [];

    if (empty($phone)) {
        $errors[] = 'Телефон обязателен';
    } else {
        $phone_digits = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone_digits) !== 11) {
            $errors[] = 'Некорректный телефон. Должно быть 11 цифр';
        } else {
            if (substr($phone_digits, 0, 1) === '8') {
                $phone_digits = '7' . substr($phone_digits, 1);
            }
            if (substr($phone_digits, 0, 1) !== '7') {
                $phone_digits = '7' . $phone_digits;
            }
            $phone = $phone_digits;
        }
    }

    if (empty($name)) $errors[] = 'Имя обязательно';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Некорректный email';
    if (empty($password) || strlen($password) < 6) $errors[] = 'Пароль должен быть не менее 6 символов';
    if ($password !== $password_repeat) $errors[] = 'Пароли не совпадают';

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Пользователь с таким email уже существует';
        }
    }

    if (empty($errors)) {
        $hashed_password = md5($password);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $hashed_password]);

            $user_id = $pdo->lastInsertId();

            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['logged_in'] = true;

            echo json_encode([
                'success' => true,
                'message' => 'Регистрация успешна!',
                'user' => [
                    'id' => $user_id,
                    'name' => $name,
                    'email' => $email
                ]
            ]);
        } catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'errors' => ['Ошибка базы данных: ' . $e->getMessage()]
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
    }
}
