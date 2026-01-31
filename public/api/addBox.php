<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../../src/boxRepo.php';
require_once __DIR__ . '/../../src/boxView.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['title'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload']);
    exit;
}

$type = $data['type'] ?? 'text';

$repo = new BoxRepository();

try {
    switch ($type) {
        case 'link':
            if (!isset($data['url'])) {
                throw new Exception('Missing url');
            }
            $id = $repo->addLinkBox($data['title'], $data['url']);
            $content = $repo->getLinkBox($id);
            break;

        case 'text':
        default:
            if (!isset($data['content'])) {
                throw new Exception('Missing content');
            }
            $id = $repo->addTextBox($data['title'], $data['content']);
            $content = $repo->getTextBox($id);
            break;
    }

    // fetch layout row DIRECTLY, not via array_filter
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM boxes WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $box = $stmt->fetch();

    if (!$box) {
        throw new Exception('Box layout not found');
    }

    ob_start();
    if ($type === 'link') {
        renderLinkBox($box, $content, true);
    } else {
        renderTextBox($box, $content, true);
    }
    $html = ob_get_clean();

    echo json_encode([
        'id' => $id,
        'html' => $html
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}