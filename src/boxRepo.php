<?php
require_once __DIR__ . '/db.php';

class BoxRepository
{

    public function getLayoutBoxes(bool $onlyEnabled = true): array
    {
        $db = getDB();

        $sql = "SELECT * FROM boxes";

        if ($onlyEnabled) {
            $sql .= " WHERE on_off = 1";
        }

        $sql .= " ORDER BY grid_y, grid_x";

        return $db->query($sql)->fetchAll();
    }

    public function getTextBox(int $boxId): array
    {
        $db = getDB();

        $stmt = $db->prepare(
            "SELECT * FROM text_boxes
            WHERE box_id = :id"
        );

        $stmt->execute([':id' => $boxId]);

        return $stmt->fetch() ?: [];
    }

    public function addLinkBox(string $title, string $url): int
    {
        $db = getDB();
        $db->beginTransaction();

        try {
            $stmt = $db->prepare(
                "INSERT INTO boxes (
                on_off,
                type,
                grid_x,
                grid_y,
                grid_w,
                grid_h)
                VALUES (
                :on_off,
                :type,
                0, 0, 1, 1)"
            );

            $stmt->execute([
                ':type' => 'link',
                ':on_off' => 1
            ]);

            $boxId = (int)$db->lastInsertId();

            $stmt = $db->prepare(
                "INSERT INTO link_boxes (
                box_id,
                title,
                url) 
                VALUES (
                :box_id,
                :title,
                :url)"
            );

            $stmt->execute([
                ':box_id' => $boxId,
                ':title' => $title,
                ':url' => $url
            ]);

            $db->commit();
            return $boxId;
        } catch (Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function getLinkBox(int $boxId): array
    {
        $db = getDB();

        $stmt = $db->prepare(
            "SELECT * FROM link_boxes
            WHERE box_id = :id"
        );

        $stmt->execute([':id'=> $boxId]);

        return $stmt->fetch() ?: [];
    }
    public function getBoxes(bool $onlyEnabled = true): array
    {
        $db = getDb();
        if ($onlyEnabled) {
            $stmt = $db->prepare(
                'SELECT id, type, on_off, grid_x, grid_y, grid_w, grid_h
             FROM boxes
             WHERE on_off = 1
             ORDER BY grid_y, grid_x'
            );
        } else {
            $stmt = $db->prepare(
                'SELECT id, type, on_off, grid_x, grid_y, grid_w, grid_h
             FROM boxes
             ORDER BY grid_y, grid_x'
            );
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function addTextBox(string $title, string $content): int
    {
        $db = getDb();
        $db->beginTransaction();
        try {
            $stmt = $db->prepare(
                'INSERT INTO boxes (
            on_off,
            type, 
            grid_x, 
            grid_y, 
            grid_w, 
            grid_h)
            VALUES ( 
            :on_off, 
            :type, 
            0, 0, 1, 1)'
            );

            $stmt->execute([
                ':type' => 'text',
                ':on_off' => 1
            ]);

            $boxId = (int) $db->lastInsertId();

            $stmt = $db->prepare(
                'INSERT INTO text_boxes (
                box_id,
                title,
                content)
                VALUES (
                :box_id,
                :title,
                :content)'
            );

            $stmt->execute([
                ':box_id' => $boxId,
                ':title' => $title,
                ':content' => $content,
            ]);

            $db->commit();
            return $boxId;
        } catch (Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function updateTextBox(int $id, string $title, string $content) 
    {
        $db = getDB();

        $stmt = $db->prepare(
            'UPDATE text_boxes
            SET content = :content,
            title = :title
            WHERE box_id = :box_id'
        );

        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':box_id' => $id,
            ]);
    }

    public function updateLinkBox(int $id, string $title, string $url) 
    {
        $db = getDB();

        $stmt = $db->prepare(
            'UPDATE link_boxes 
            SET title = :title,
            url = :url
            WHERE box_id = :box_id'
        );

        $stmt->execute([
            ':title' => $title,
            ':url' => $url,
            ':box_id' => $id,
        ]);
    }

    public function updateEnabled(int $id, bool $on_off)
    {
        $db = getDb();

        $stmt = $db->prepare(
            'UPDATE boxes
             SET 
             on_off = :on_off
             WHERE id = :id'
        );

        $stmt->execute([
            ':on_off' => $on_off ? 1 : 0,
            ':id' => $id
        ]);
    }

    public function updatePosition(int $id, int $x, int $y, int $w, int $h)
    {
        $db = getDB();

        $stmt = $db->prepare(
            'UPDATE boxes
            SET grid_x = :x,
            grid_y = :y,
            grid_w = :w,
            grid_h = :h
            WHERE id = :id'
        );

        $stmt->execute([
            ':x' => $x,
            ':y' => $y,
            ':w' => $w,
            ':h' => $h,
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