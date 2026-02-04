<!-- 
 
add:
admin dashboard controls for main landing

-->

<?php
session_start();
$dotenvPath = __DIR__ . '/../.env';
$isJson = str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json');
$env = parse_ini_file($dotenvPath);


if (!file_exists($dotenvPath)) {
    throw new Exception('.env not found');
}

if ($isJson) {
    $data = json_decode(file_get_contents('php://input'), true);
} else {
    $data = $_POST;
}

require_once __DIR__ . '/../src/boxRepo.php';
require_once __DIR__ . '/../src/boxView.php';


/* ----- LOGIN HANDLER ----- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['password'])) {
    if ($data['password'] === $env['ADMIN_PASSWORD']) { #change password later
        $_SESSION['admin'] = true;

        header('Location: admin.php');
        exit;
    }
}
/* ----- BLOCK ACCESS IF NOT LOGGED IN ----- */
if (!isset($_SESSION['admin'])): ?>
    <form method="post">
        <input type="password" name="password" placeholder="Password">
        <button type="submit">Login</button>
    </form>
    <?php
    exit;
endif;

/* ----- CONFIRM ADMIN ----- */
$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

/* ----- BOX HANDLER ----- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['action'])) {
    $boxRepo = new BoxRepository();



    // add text box
    if ($data['action'] === 'add') {
        $boxRepo->addTextBox(
            $data['title'] ?? '',
            $data['content'] ?? '',
        );
        header('Location: admin.php');
        exit;
    }

    // update box 
    if ($data['action'] === 'update') {
        if ($data['type'] === 'text') {
            # code...
            $boxRepo->updateTextBox(
                (int) $data['id'],
                $data['title'] ?? '',
                $data['content'] ?? ''
            );
        }

        if ($data['type'] === 'link') {
            // trim url and sanitize the output
            $url = trim($data['url'] ?? '');
            if ($url && !str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                $url = "https://$url";
            }
            $meta = fetchLinkMetadata($url);
            $title = trim($data['title'] ?? '');
            $desc = $meta['description'] ?? '';
            if ($title === '' && isset($meta['title'])) {
                $title = $meta['title'];
            }

            $boxRepo->updateLinkBox(
                (int) $data['id'],
                $title,
                $url,
                $desc
            );
         /*    var_dump($meta);
            exit; */
        }

        exit;
    }

    // delete box
    if ($data['action'] === 'delete') {
        $boxRepo->deleteBox((int) $data['id']);
        header('Location: admin.php');
        exit;
    }
}


/* <!-- Edit Boxes --> */
$boxRepo = new BoxRepository();
$boxes = $boxRepo->getLayoutBoxes(false);

?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="styles.css">
    <link href=" https://cdn.jsdelivr.net/npm/gridstack@12.4.2/dist/gridstack.min.css " rel="stylesheet">
</head>

<body data-page="admin">
    <h1>Manage Boxes</h1>

    <?php renderAddBoxButtons(); ?>

    <br>


    <div class="grid-stack admin-mode">
        <?php foreach ($boxes as $box) {
            switch ($box['type']) {
                case 'text':
                    $content = $boxRepo->getTextBox($box['id']);
                    renderTextBox($box, $content, $isAdmin);
                    break;
                case 'link':
                    $content = $boxRepo->getLinkBox($box['id']);
                    renderLinkBox($box, $content, true);
                    break;
            }
        }
        ?>
    </div>
    <script>
        window.IS_ADMIN = true;
    </script>
    <script src=" https://cdn.jsdelivr.net/npm/gridstack@12.4.2/dist/gridstack-all.min.js "></script>
    <script src="script.js"></script>
</body>