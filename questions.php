<?php
require 'config.php';

header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (!isset($_GET['quiz_id'])) {
            exit(json_encode(['error' => 'Missing quiz_id parameter']));
        }
        $stmt = $pdo->prepare('SELECT * FROM Questions WHERE quiz_id = ?');
        $stmt->execute([$_GET['quiz_id']]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'));
        $stmt = $pdo->prepare('INSERT INTO Questions (quiz_id, question_text) VALUES (?, ?)');
        $stmt->execute([$data->quiz_id, $data->question_text]);
        echo json_encode(['message' => 'Question created', 'question_id' => $pdo->lastInsertId()]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'));
        $stmt = $pdo->prepare('UPDATE Questions SET question_text = ? WHERE question_id = ?');
        $stmt->execute([$data->question_text, $data->question_id]);
        echo json_encode(['message' => 'Question updated']);
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'));
        $stmt = $pdo->prepare('DELETE FROM Questions WHERE question_id = ?');
        $stmt->execute([$data->question_id]);
        echo json_encode(['message' => 'Question deleted']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>
