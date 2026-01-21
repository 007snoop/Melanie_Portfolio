<?php
require_once __DIR__ . '/db.php';

class BoxRepository
{
    public function getBoxes(bool $onlyEnabled = true): array
    {
        $db = getDb();
        if ($onlyEnabled) {
            $stmt = $db->prepare(
                'SELECT id, title, content, position, on_off, size
             FROM boxes
             WHERE on_off = 1
             ORDER BY position ASC'
            );
        } else {
            $stmt = $db->prepare(
                'SELECT id, title, content, position, on_off, size
             FROM boxes
             ORDER BY position ASC'
            );
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function addBox(string $title, string $content, int $position = 0, string $size = '1x1'): int
    {
        $db = getDb();

        $stmt = $db->prepare(
            'INSERT INTO boxes (title, content, position, on_off, size) VALUES (:title, :content, :position, :on_off, :size)'
        );
        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':position' => $position,
            ':on_off' => 1,
            ':size' => $size
        ]);

        return (int) $db->lastInsertId();
    }

    public function updateBox(int $id, string $title, string $content, int $position, bool $on_off, string $size)
    {
        $db = getDb();

        $stmt = $db->prepare(
            'UPDATE boxes
             SET title = :title, content = :content, position = :position, on_off = :on_off, size = :size
             WHERE id = :id'
        );

        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':position' => $position,
            ':on_off' => $on_off ? 1 : 0,
            ':size' => $size,
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