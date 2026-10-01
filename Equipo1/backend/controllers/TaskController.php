<?php
declare(strict_types=1);

require_once __DIR__ . '/../services/TaskService.php';

class TaskController {
    public function __construct(private TaskService $service) {}

    public function handle(): void {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
            $body = json_decode(file_get_contents('php://input'), true) ?? [];

            switch ($method) {
                case 'GET':
                    if ($id) {
                        $task = $this->service->get($id);
                        if (!$task) $this->respond(['message' => 'Tarea no encontrada.'], 404);
                        $this->respond(['task' => $task]);
                    }
                    $this->respond(['tasks' => $this->service->list()]);
                    break;

                case 'POST':
                    $newId = $this->service->create($body);
                    $this->respond([
                        'message' => 'Tarea creada correctamente.',
                        'task' => $this->service->get($newId)
                    ], 201);
                    break;

                case 'PUT':
                    if (!$id) $this->respond(['message' => 'Falta el id de la tarea.'], 400);
                    if (!$this->service->update($id, $body)) {
                        $this->respond(['message' => 'No se realizaron cambios o la tarea no existe.'], 404);
                    }
                    $this->respond([
                        'message' => 'Tarea actualizada correctamente.',
                        'task' => $this->service->get($id)
                    ]);
                    break;

                case 'DELETE':
                    if (!$id) $this->respond(['message' => 'Falta el id de la tarea.'], 400);
                    if (!$this->service->delete($id)) {
                        $this->respond(['message' => 'Tarea no encontrada.'], 404);
                    }
                    $this->respond(['message' => 'Tarea eliminada correctamente.']);
                    break;

                default:
                    $this->respond(['message' => 'Método no permitido.'], 405);
            }
        } catch (InvalidArgumentException $e) {
            $this->respond(['message' => $e->getMessage()], 422);
        } catch (Throwable $e) {
            $this->respond(['message' => 'Error interno del servidor.', 'detail' => $e->getMessage()], 500);
        }
    }

    private function respond(array $data, int $status = 200): never {
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
