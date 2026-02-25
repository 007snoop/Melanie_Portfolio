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
            'SELECT display_name, bio, a_url 
            FROM profile
            LIMIT 1'
        );

        $STMT->execute();
        return $STMT->fetch() ?: null;
    }

    public function updateProfile(string $displayName, ?string $bio, ?string $ImgP = null): void {
        $DB = getDB();

        if ($ImgP !== null) {
            $stmt = $DB->prepare("
            UPDATE profile
            SET display_name = ?,
            bio = ?,
            a_url = ?
            WHERE id = 1
            ");

            $stmt->execute([$displayName, $bio, $ImgP]);
        } else {
            $stmt = $DB->prepare("
            UPDATE profile
            SET display_name = ?,
            bio = ?
            WHERE id = 1
            ");

            $stmt->execute([$displayName, $bio]);
        }
    }
}

?>