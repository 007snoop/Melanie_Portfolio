<!-- 
 
PDO connection
 
add GetDB() function 

-->

<?php 
function getDB(): PDO {
    static $DB = null;

    if ($DB === null) {
        $HOST = 'localhost';
        $DB_NAME = 'xxxx';
        $USER = 'xxxx';
        $PASS = 'xxxx';

        $DSN = "mysql:host=$HOST;dbname=$DB_NAME;charset=utf8mb4";

        $DB = new PDO($DSN, $USER, $PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    return $DB;
}

?>