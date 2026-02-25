<!-- 
 
add login functionality

-->
<?php 
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

session_start();


$dotenvPath = __DIR__.'/../.env';

if (!file_exists($dotenvPath)) {
    throw new Exception('.env not found.');
}

$env = parse_ini_file($dotenvPath);

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    if (
        isset($env['ADMIN_PASSWORD_HASH']) &&
        password_verify($password, $env['ADMIN_PASSWORD_HASH'])
    ) {
        session_regenerate_id(true);

        $_SESSION['admin'] = true;

        header('Location: admin.php');
        exit;
    } else {
        sleep(1);
        $error = "Invalid Password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>