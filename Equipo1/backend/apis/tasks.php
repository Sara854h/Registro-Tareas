<?php
declare(strict_types=1);

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'message' => 'Debes iniciar sesión para acceder a las tareas.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once __DIR__ . '/../connections/connection.php';
require_once __DIR__ . '/../controllers/TaskController.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $db = getConnection();
    $service = new TaskService($db);
    $controller = new TaskController($service);
    $controller->handle();
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'message' => 'No se pudo conectar con la base de datos.',
        'detail' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
