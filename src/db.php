<?php 

$dotenvPath = __DIR__ . '/../.env';
if (!file_exists($dotenvPath)) {
    throw new Exception('.env not found');
}

$env = parse_ini_file($dotenvPath);

foreach (['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'] as $key) {
    if (!isset($env[$key])) {
        throw new Exception("Missing $key in .env file");
    }
}
function getDB(): PDO {
    global $env;
    static $DB = null;

    if ($DB === null) {
        $DSN = "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8mb4";

        $DB = new PDO($DSN, $env['DB_USER'], $env['DB_PASS'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    return $DB;
}

?>