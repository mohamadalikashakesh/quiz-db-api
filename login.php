<?php
require 'config.php';

$data = json_decode(file_get_contents('php://input'));

if (!isset($data->email, $data->password)) {
    exit(json_encode(['error' => 'Missing fields']));
}

$stmt = $pdo->prepare('SELECT user_id, username, password_hash, privileges FROM Users WHERE email = ?');
$stmt->execute([$data->email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($data->password, $user['password_hash'])) {
    echo json_encode([
        'message' => 'Login successful',
        'user_id' => $user['user_id'],
        'username' => $user['username'],
        'privileges' => $user['privileges']
    ]);
} else {
    echo json_encode(['error' => 'Invalid credentials']);
}
