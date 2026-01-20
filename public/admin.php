<!-- 
 
add:
admin dashboard controls for main landing

-->

<?php
session_start();
require_once __DIR__ . '/../src/boxRepo.php';
require_once __DIR__ . '/../src/boxView.php';

/* ----- LOGIN HANDLER ----- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === 'password') { #change password later
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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $boxRepo = new BoxRepository();
    $boxRepo->updateBox(
        (int) $_POST['id'],
        $_POST['title'],
        $_POST['content'],
        (int) $_POST['position'],
        isset($_POST['on_off']) ? 1 : 0
    );
    header('Location: admin.php');
    exit;
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
<div class="bento-container admin-mode">
    <?php foreach ($boxes as $box):
        renderBox($box, true);
    endforeach; ?>
</div>