<!-- 
 
add:
admin dashboard controls for main landing

-->

<?php
session_start();
$dotenvPath = __DIR__ . '/../.env';

if (!file_exists($dotenvPath)) {
    throw new Exception('.env not found');
}

$env = parse_ini_file($dotenvPath);

require_once __DIR__ . '/../src/boxRepo.php';
require_once __DIR__ . '/../src/boxView.php';


/* ----- LOGIN HANDLER ----- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === $env['ADMIN_PASSWORD']) { #change password later
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

/* ----- BOX HANDLER ----- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $boxRepo = new BoxRepository();

    // add box
    if ($_POST['action'] === 'add') {
        $boxRepo->addBox(
            $_POST['title'],
            $_POST['content'],
            (int) $_POST['position']
        );
        header('Location: admin.php');
        exit;
    }

    // update box 
    if ($_POST['action'] === 'update') {
        if (trim($_POST['title']) === '' || trim($_POST['content']) === '') {
            header('Location: admin.php');
            exit;
        }

        $boxRepo->updateBox(
            (int) $_POST['id'],
            $_POST['title'],
            $_POST['content'],
            (int) $_POST['position'],
            isset($_POST['on_off']) ? 1 : 0,
            $_POST['size'] ?? '1x1'
        );
        header('Location: admin.php');
        exit;
    }

    // delete box
    if ($_POST['action'] === 'delete') {
        $boxRepo->deleteBox((int) $_POST['id']);
        header('Location: admin.php');
        exit;
    }
}


/* <!-- Edit Boxes --> */
$boxRepo = new BoxRepository();
$boxes = $boxRepo->getBoxes(false);

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="styles.css">
    
</head>

<h1>Manage Boxes</h1>

<?php renderAddBoxForm(); ?>

<br> 


<div class="bento-container admin-mode">
    <?php foreach ($boxes as $box):
        renderBox($box, true);
    endforeach; ?>
</div>

<script src="script.js"></script>