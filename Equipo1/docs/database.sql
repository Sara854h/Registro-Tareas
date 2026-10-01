CREATE DATABASE IF NOT EXISTS tareas_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE tareas_db;

CREATE TABLE IF NOT EXISTS usuario (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    contraseña VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tareas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_tarea VARCHAR(150) NOT NULL,
    descripcion TEXT NULL,
    estado ENUM('pendiente', 'en proceso', 'finalizada') NOT NULL DEFAULT 'pendiente',
    prioridad ENUM('baja', 'media', 'alta') NOT NULL DEFAULT 'media',
    fecha_entrega DATE NOT NULL,
    INDEX idx_fecha_entrega (fecha_entrega),
    INDEX idx_estado (estado)
) ENGINE=InnoDB;

-- La columna descripcion se incluye porque el formulario solicitado la captura.
-- La estructura base solicitada para tareas conserva id, nombre_tarea, estado,
-- prioridad y fecha_entrega.

-- Endpoints de tareas:
-- GET    /backend/apis/tasks.php             -> lista todas
-- GET    /backend/apis/tasks.php?id=1        -> consulta una
-- POST   /backend/apis/tasks.php             -> crea
-- PUT    /backend/apis/tasks.php?id=1        -> actualiza
-- DELETE /backend/apis/tasks.php?id=1        -> elimina
