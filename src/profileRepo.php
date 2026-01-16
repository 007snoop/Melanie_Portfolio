<!-- 
 
add:
getProfile()
updateProfile()

-->

<?php 
require_once __DIR__ . '/db.php';

class profileRepo {
    public function getProfile(): ?array {
        $DB = getDB();

        $STMT = $DB->prepare(
            'SELECT display_name, a_url 
            FROM profile
            LIMIT 1'
        );

        $STMT->execute();
        return $STMT->fetch() ?: null;
    }
}

?>