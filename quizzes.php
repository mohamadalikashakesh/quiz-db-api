<?php
require 'config.php';

header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $pdo->query('SELECT * FROM Quizzes');
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'));
        $stmt = $pdo->prepare('INSERT INTO Quizzes (title, description, category_id) VALUES (?, ?, ?)');
        $stmt->execute([$data->title, $data->description, $data->category_id]);
        echo json_encode(['message' => 'Quiz created', 'quiz_id' => $pdo->lastInsertId()]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'));
        $stmt = $pdo->prepare('UPDATE Quizzes SET title=?, description=?, category_id=? WHERE quiz_id=?');
        $stmt->execute([$data->title, $data->description, $data->category_id, $data->quiz_id]);
        echo json_encode(['message' => 'Quiz updated']);
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'));
        $stmt = $pdo->prepare('DELETE FROM Quizzes WHERE quiz_id=?');
        $stmt->execute([$data->quiz_id]);
        echo json_encode(['message' => 'Quiz deleted']);
        break;

    default:
        echo json_encode(['error' => 'Method not allowed']);
        http_response_code(405);
        break;
}
