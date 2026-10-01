<?php
declare(strict_types=1);

class Task {
    public static function all(PDO $db): array {
        $stmt = $db->query(
            'SELECT id, nombre_tarea, descripcion, estado, prioridad, fecha_entrega
             FROM tareas ORDER BY fecha_entrega ASC, id DESC'
        );
        return $stmt->fetchAll();
    }

    public static function find(PDO $db, int $id): ?array {
        $stmt = $db->prepare(
            'SELECT id, nombre_tarea, descripcion, estado, prioridad, fecha_entrega
             FROM tareas WHERE id = ?'
        );
        $stmt->execute([$id]);
        $task = $stmt->fetch();
        return $task ?: null;
    }

    public static function create(PDO $db, array $data): int {
        $stmt = $db->prepare(
            'INSERT INTO tareas (nombre_tarea, descripcion, estado, prioridad, fecha_entrega)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['nombre_tarea'],
            $data['descripcion'] ?? '',
            $data['estado'] ?? 'pendiente',
            $data['prioridad'],
            $data['fecha_entrega']
        ]);
        return (int)$db->lastInsertId();
    }

    public static function update(PDO $db, int $id, array $data): bool {
        $fields = [];
        $values = [];

        foreach (['nombre_tarea', 'descripcion', 'prioridad', 'fecha_entrega', 'estado'] as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "$field = ?";
                $values[] = $data[$field];
            }
        }

        if (!$fields) return false;
        $values[] = $id;
        $stmt = $db->prepare('UPDATE tareas SET ' . implode(', ', $fields) . ' WHERE id = ?');
        $stmt->execute($values);
        return $stmt->rowCount() > 0;
    }

    public static function delete(PDO $db, int $id): bool {
        $stmt = $db->prepare('DELETE FROM tareas WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
