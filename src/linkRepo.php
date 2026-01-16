<!-- 
 
to add:
getVisibleLinks()
getAllLinks()
createLink()
updateLink()
deleteLink()

-->

<?php 

require_once __DIR__ . '/db.php';

class LinkRepo {
    public function getVisibleLinks(): array {
        $DB = getDB();

        $STMT = $DB -> prepare(
            'SELECT title, l_url
            FROM links
            WHERE on_off = 1
            ORDER BY position ASC'
        );

        $STMT -> execute();
        return $STMT -> fetchAll();
    }
}