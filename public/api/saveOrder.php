<?php 
session_start();
require_once __DIR__ . '/../../src/boxRepo.php';
$repo = new BoxRepository();
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($_SESSION['admin'])) {
    http_response_code(403);
    exit;
}

if (!is_array($data)) {
    http_response_code(400);
    exit;
}

foreach ($data as $item) {
    $repo->updatePosition(
        (int)$item['id'],
        (int)$item['position']

    );
}