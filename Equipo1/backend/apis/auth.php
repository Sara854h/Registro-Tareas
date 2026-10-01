<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/../connections/connection.php';

header('Content-Type: application/json; charset=utf-8');

function respond(array $data, int $status = 200): never {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function getRequestBody(): array {
    $raw = file_get_contents('php://input');
    if ($raw === false || trim($raw) === '') {
        return $_POST;
    }

    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

try {
    $db = getConnection();
    $body = getRequestBody();
    $action = strtolower(trim((string)($body['action'] ?? '')));

    if ($action === 'register') {
        $nombre = trim((string)($body['nombre'] ?? ''));
        $correo = strtolower(trim((string)($body['correo'] ?? '')));
        $password = (string)($body['password'] ?? '');

        if ($nombre === '') {
            respond(['message' => 'Escribe tu nombre.'], 422);
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            respond(['message' => 'Escribe un correo electrónico válido.'], 422);
        }

        if (strlen($password) < 6) {
            respond(['message' => 'La contraseña debe tener mínimo 6 caracteres.'], 422);
        }

        $check = $db->prepare('SELECT id FROM usuario WHERE correo = ? LIMIT 1');
        $check->execute([$correo]);

        if ($check->fetch()) {
            respond(['message' => 'El correo ya está registrado.'], 409);
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        if ($hash === false) {
            respond(['message' => 'No fue posible proteger la contraseña.'], 500);
        }

        $stmt = $db->prepare('INSERT INTO usuario (nombre, correo, contraseña) VALUES (?, ?, ?)');
        $stmt->execute([$nombre, $correo, $hash]);

        respond([
            'message' => 'Registro exitoso. Ya puedes iniciar sesión.',
            'id' => (int)$db->lastInsertId()
        ]);
    }

    if ($action === 'login') {
        $correo = strtolower(trim((string)($body['correo'] ?? '')));
        $password = (string)($body['password'] ?? '');

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || $password === '') {
            respond(['message' => 'Escribe tu correo y contraseña.'], 422);
        }

        $stmt = $db->prepare('SELECT id, nombre, correo, contraseña FROM usuario WHERE correo = ? LIMIT 1');
        $stmt->execute([$correo]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, (string)$user['contraseña'])) {
            respond(['message' => 'Correo o contraseña incorrectos.'], 401);
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_name'] = $user['nombre'];
        $_SESSION['user_email'] = $user['correo'];

        unset($user['contraseña']);
        respond(['message' => 'Inicio de sesión correcto.', 'user' => $user]);
    }

    if ($action === 'session') {
        if (!isset($_SESSION['user_id'])) {
            respond(['logueado' => false]);
        }

        $stmt = $db->prepare('SELECT id, nombre, correo FROM usuario WHERE id = ? LIMIT 1');
        $stmt->execute([(int)$_SESSION['user_id']]);
        $user = $stmt->fetch();

        if (!$user) {
            $_SESSION = [];
            respond(['logueado' => false]);
        }

        respond(['logueado' => true, 'user' => $user]);
    }

    if ($action === 'logout') {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        respond(['message' => 'Sesión cerrada correctamente.']);
    }

    respond(['message' => 'Acción no válida.'], 400);
} catch (PDOException $e) {
    error_log('Tareas auth PDO: ' . $e->getMessage());
    respond([
        'message' => 'No se pudo conectar o guardar en MariaDB. Verifica que el usuario equipo1 tenga acceso a tareas_db.',
        'detail' => $e->getMessage()
    ], 500);
} catch (Throwable $e) {
    error_log('Tareas auth: ' . $e->getMessage());
    respond([
        'message' => 'Error interno del servidor.',
        'detail' => $e->getMessage()
    ], 500);
}
