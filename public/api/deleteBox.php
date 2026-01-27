<?php
session_start();
require_once __DIR__ . '/../../src/boxRepo.php';
header('Content-Type: application/json');

if (!isset($_SESSION['admin'])) {
    http_response_code(403);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $repo = new BoxRepository();
    
    if (!isset($data['id'])) {
        http_response_code(400);
        exit;
    }

    $repo->deleteBox((int)$data['id']);

    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Box deleted']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>