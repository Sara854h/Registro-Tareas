<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/Task.php';

class TaskService {
    public function __construct(private PDO $db) {}

    public function list(): array {
        return Task::all($this->db);
    }

    public function get(int $id): ?array {
        return Task::find($this->db, $id);
    }

    public function create(array $data): int {
        $this->validate($data, false);
        return Task::create($this->db, $data);
    }

    public function update(int $id, array $data): bool {
        if (array_key_exists('prioridad', $data) || array_key_exists('fecha_entrega', $data) || array_key_exists('nombre_tarea', $data)) {
            $this->validate($data, true);
        }
        return Task::update($this->db, $id, $data);
    }

    public function delete(int $id): bool {
        return Task::delete($this->db, $id);
    }

    private function validate(array $data, bool $partial): void {
        $required = ['nombre_tarea', 'prioridad', 'fecha_entrega'];
        if (!$partial) {
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new InvalidArgumentException("El campo $field es obligatorio.");
                }
            }
        }
        if (isset($data['prioridad']) && !in_array($data['prioridad'], ['baja', 'media', 'alta'], true)) {
            throw new InvalidArgumentException('La prioridad no es válida.');
        }
        if (isset($data['estado']) && !in_array($data['estado'], ['pendiente', 'en proceso', 'finalizada'], true)) {
            throw new InvalidArgumentException('El estado no es válido.');
        }
    }
}
