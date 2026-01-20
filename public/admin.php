<!-- 
 
add:
admin dashboard controls for main landing

-->

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === 'devpassword') { #change password later
        $_SESSION['admin'] = true;

        header('Location: admin.php');
        exit;
    }
}

if (!isset($_SESSION['admin'])):
    ?>
    <form method="post">
        <input type="password" name="password" placeholder="Password">
        <button type="submit">Login</button>
    </form>
    <?php
    exit;
    ?>
 
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $boxRepo->updateBox(
        (int)$_POST['id'],
        $_POST['title'],
        $_POST['content'],
        (int)$_POST['position'],
        isset($_POST['on_off']) ? 1 : 0
    );
}
endif;
?>


<!-- Edit Boxes -->
<?php 
require_once __DIR__ . '/../src/boxRepo.php';

$boxRepo = new BoxRepository();
$boxes = $boxRepo->getBoxes();

?>

<h1>Manage Boxes</h1>

<?php foreach ($boxes as $box): ?>
    <form method="post">
        <input type="hidden" name="id" value="<?= $box['id'] ?>">

        <label>
            Title<br>
            <input type="text" name="title" value="<?= htmlspecialchars($box['content']) ?>">
        </label><br><br>

        <label>
            Content<br>
            <textarea name="content" rows="4"><?= htmlspecialchars($box['content']) ?></textarea>
        </label><br><br>

        <label>
            Position<br>
            <input type="number" name="Position" value="<?= $box['position'] ?>">
        </label><br><br>

        <label>
            Enabled
            <input type="checkbox" name="on_off" <?= $box['on_off'] ? 'checkbox' : '' ?>>
        </label><br><br>

        <button type="submit" name="action" value="update">Save</button>
    </form>

<?php endforeach; ?>