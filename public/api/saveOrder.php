<?php 
session_start();
require_once __DIR__ . '/../../src/boxRepo.php';
$repo = new BoxRepository();
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($_SESSION['admin'])) {
    http_response_code(403);
    exit;
}

if (!isset($data['order']) || !is_array($data['order'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload']);
    exit;
}

foreach ($data['order'] as $item) {
    if (!isset($item['id'], $item['x'], $item['y'], $item['w'], $item['h'])) {
        continue;
    }

    $repo->updatePosition(
        (int)$item['id'],
        (int)$item['x'],
        (int)$item['y'],
        (int)$item['w'],
        (int)$item['h']
    );
}