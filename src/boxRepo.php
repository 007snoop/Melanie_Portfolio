<?php
require_once __DIR__ . '/db.php';

class BoxRepository
{
    public function getBoxes(): array
    {
        $db = getDb();

        $stmt = $db->prepare(
            'SELECT id, title, content, position, on_off
             FROM boxes
             WHERE on_off = 1
             ORDER BY position ASC'
        );

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function addBox(string $title, string $content, int $position = 0): int
    {
        $db = getDb();

        $stmt = $db->prepare(
            'INSERT INTO boxes (title, content, position) VALUES (:title, :content, :position)'
        );
        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':position' => $position
        ]);

        return (int)$db->lastInsertId();
    }

    public function updateBox(int $id, string $title, string $content, int $position, bool $on_off)
    {
        $db = getDb();

        $stmt = $db->prepare(
            'UPDATE boxes
             SET title = :title, content = :content, position = :position, on_off = :enabled
             WHERE id = :id'
        );

        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':position' => $position,
            ':on_off' => $on_off,
            ':id' => $id
        ]);
    }

    public function deleteBox(int $id)
    {
        $db = getDb();
        $stmt = $db->prepare('DELETE FROM boxes WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}