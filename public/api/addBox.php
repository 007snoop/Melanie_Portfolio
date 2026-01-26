<?php

session_start();
require_once __DIR__ . '/../../src/boxRepo.php';
header('Content-Type: application/json');

if (!isset($_SESSION['admin'])) {
    http_response_code(403);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['title'], $data['content'])) {
    http_response_code(400);
    exit;
    # code...
}

$repo = new BoxRepository();

$id = $repo->addTextBox(
    $data['title'],
    $data['content'] ?? '',
    $data['type'] ?? 'text'
);

echo json_encode(['id' => $id]);