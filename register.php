<?php
require 'config.php';

$data = json_decode(file_get_contents('php://input'));

if (!isset($data->username, $data->email, $data->password)) {
    exit(json_encode(['error' => 'Missing fields']));
}

$stmt = $pdo->prepare('INSERT INTO Users (username, email, password_hash) VALUES (?, ?, ?)');
$password_hash = password_hash($data->password, PASSWORD_DEFAULT);

try {
    $stmt->execute([$data->username, $data->email, $password_hash]);
    echo json_encode(['message' => 'Registration successful']);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
